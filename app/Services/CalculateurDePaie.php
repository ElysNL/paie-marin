<?php

namespace App\Services;

use App\Models\{Paie, AffectationMarin, BulletinPaie, BulletinElemPaie, BulletinCotisation, ElemPaie, Cotisation, IgrParametre, TauxChange, Devise};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalculateurDePaie
{
    public function calculateBulletin(Paie $paie, AffectationMarin $affectation): BulletinPaie
    {
        DB::beginTransaction();
        try {
            $bulletin = $this->createBulletin($paie, $affectation);

            $jours = $this->calculateDays($paie, $affectation, $bulletin);
            $totalJours = array_sum(array_column($jours, 'nombre'));
            $bulletin->total_jours = $totalJours;

            $tauxJournalier = $affectation->taux_journalier;
            $salaireBase = $tauxJournalier * $totalJours;
            $this->addGain($bulletin, 'SAL_BASE', $salaireBase);

            $this->addGain($bulletin, 'PRIME_NAVIGATION', config('paie.prime_navigation', 5000));

            // Recharger depuis la DB pour garantir la cohérence des éléments
            $bulletin->unsetRelation('elements');
            $bulletin->load('elements.elemPaie');
            $brut = $bulletin->elements->where('elemPaie.type', 'GAIN')->sum('montant');
            $bulletin->total_gains = $brut;
            $bulletin->total_brut = $brut;

            $cotisationsSalariales = $this->calculateCotisations($bulletin, $brut, 'salarial');
            $bulletin->total_cotisations_salariales = $cotisationsSalariales;

            // Assiette IGR = revenu brut (régime malgache : cotisations non déductibles)
            $baseImposable = $brut;

            $igrBrut = $this->calculateIGR($baseImposable);
            $abattement = $this->abattementPour($affectation->employe);
            $igrNet = max(0, $igrBrut - $abattement);
            $this->addRetenue($bulletin, 'IGR', $igrNet);

            $delegations = $this->getActiveDelegations($affectation->employe_id, $paie->date_debut);
            foreach ($delegations as $del) {
                $this->addRetenue($bulletin, 'DELEGATION', $del->montant);
                $bulletin->delegations()->create([
                    'delegation_id' => $del->id,
                    'montant' => $del->montant
                ]);
            }

            // Retenues (IGR, délégations) + cotisations salariales
            $bulletin->unsetRelation('elements');
            $bulletin->load('elements.elemPaie');
            // Retenues dans elements (type=RETENUE) vs cotisations dans bulletins_cotisations
            $totalRetenues = $bulletin->elements->where('elemPaie.type', 'RETENUE')->sum('montant')
                             + $bulletin->total_cotisations_salariales;
            $bulletin->total_retenues = $totalRetenues;

            $net = $brut - $totalRetenues;

            $avances = $this->getActiveAdvances($affectation->employe_id, $paie->date_debut, $paie->date_fin);
            $montantAvances = 0;
            foreach ($avances as $avance) {
                $montantAvances += $avance->solde;
                $bulletin->remboursementsAvances()->create([
                    'avance_id' => $avance->id,
                    'montant' => $avance->solde,
                ]);
            }
            $netAPayer = $net - $montantAvances;
            $bulletin->net_a_payer = $netAPayer;

            $cotisationsPatronales = $this->calculateCotisations($bulletin, $brut, 'patronal');
            $bulletin->total_cotisations_patronales = $cotisationsPatronales;
            $bulletin->cout_total_employeur = $brut + $cotisationsPatronales;

            $this->freezeTauxChange($bulletin, $paie, $affectation);

            $bulletin->statut = 'calcule';
            $bulletin->save();

            DB::commit();
            return $bulletin;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function createBulletin($paie, $affectation)
    {
        return BulletinPaie::create([
            'paie_id' => $paie->id,
            'employe_id' => $affectation->employe_id,
            'affectation_id' => $affectation->id,
            'navire_id' => $affectation->navire_id,
            'devise_source_id' => $affectation->devise_id,
            'statut' => 'brouillon'
        ]);
    }

    private function calculateDays($paie, $affectation, $bulletin)
    {
        $start = Carbon::parse($paie->date_debut);
        $end = Carbon::parse($paie->date_fin);
        $totalDays = $start->diffInDays($end) + 1;

        $days = [[
            'date' => $start->toDateString(),
            'type_jour' => 'NORMAL',
            'nombre' => $totalDays,
            'taux' => 0,
            'montant' => 0,
        ]];

        foreach ($days as $day) {
            $bulletin->jours()->create($day);
        }

        return $days;
    }

    private function addGain($bulletin, $code, $montant, $description = null)
    {
        $elem = ElemPaie::where('code', $code)->first();
        if (!$elem) return;
        $bulletin->elements()->create([
            'elem_paie_id' => $elem->id,
            'montant' => $montant,
            'description' => $description,
            'ordre' => $elem->ordre,
        ]);
    }

    private function addRetenue($bulletin, $code, $montant)
    {
        $elem = ElemPaie::where('code', $code)->first();
        if (!$elem) return;
        $bulletin->elements()->create([
            'elem_paie_id' => $elem->id,
            'montant' => $montant,
            'ordre' => $elem->ordre,
        ]);
    }

    private function calculateCotisations($bulletin, $assiette, $type)
    {
        $cotisations = Cotisation::where('actif', true)->get();
        $total = 0;
        foreach ($cotisations as $cot) {
            $taux = $type === 'salarial' ? $cot->taux_salarial : $cot->taux_patronal;
            $plafond = $type === 'salarial' ? $cot->plafond_salarial : $cot->plafond_patronal;
            $base = min($assiette, $plafond ?? PHP_FLOAT_MAX);
            $montant = $base * ($taux / 100);
            $total += $montant;

            $data = [
                'bulletin_id' => $bulletin->id,
                'cotisation_id' => $cot->id,
                'assiette' => $base,
            ];
            if ($type === 'salarial') {
                $data['taux_salarial'] = $taux;
                $data['montant_salarial'] = $montant;
            } else {
                $data['taux_patronal'] = $taux;
                $data['montant_patronal'] = $montant;
            }
            $bulletin->cotisations()->updateOrCreate(
                ['bulletin_id' => $bulletin->id, 'cotisation_id' => $cot->id],
                $data
            );
        }
        return $total;
    }

    private function calculateIGR($baseImposable)
    {
        $tranches = IgrParametre::where('date_debut', '<=', Carbon::today())
            ->where(function ($q) {
                $q->whereNull('date_fin')->orWhere('date_fin', '>=', Carbon::today());
            })
            ->orderBy('tranche_inf')
            ->get();

        $impot = 0;
        foreach ($tranches as $tranche) {
            if ($baseImposable <= $tranche->tranche_inf) continue;
            $base = min($baseImposable, $tranche->tranche_sup ?? $baseImposable) - $tranche->tranche_inf;
            $impot += $base * ($tranche->taux_igr / 100);
        }
        return $impot;
    }

    /**
     * Abattement IGR = charges × montant/charge (config paie.abattement_par_charge).
     */
    public function abattementPour($employe)
    {
        $nbreCharges = $employe ? (int) $employe->nbre_charges : 0;
        return $nbreCharges * (int) config('paie.abattement_par_charge', 2000);
    }

    private function getActiveAdvances($employeId, $dateDebut, $dateFin)
    {
        return \App\Models\Avance::where('employe_id', $employeId)
            ->where('statut', 'en_cours')
            ->where('date_avance', '<=', $dateFin)
            ->get();
    }

    private function getActiveDelegations($employeId, $dateDebut)
    {
        return \App\Models\Delegation::where('employe_id', $employeId)
            ->where('statut', 'actif')
            ->where('date_debut', '<=', $dateDebut)
            ->where(function ($q) use ($dateDebut) {
                $q->whereNull('date_fin')->orWhere('date_fin', '>=', $dateDebut);
            })
            ->get();
    }

    private function freezeTauxChange($bulletin, $paie, $affectation)
    {
        $devisePaiement = Devise::where('code', config('paie.devise_paiement', 'MGA'))->first();
        if (!$devisePaiement) return;

        // Si la devise source est déjà la devise de paiement, pas besoin de taux
        if ($affectation->devise_id === $devisePaiement->id) {
            $bulletin->devise_source_id = $affectation->devise_id;
            $bulletin->devise_paiement_id = $devisePaiement->id;
            return;
        }

        $tauxChange = TauxChange::where('devise_source_id', $affectation->devise_id)
            ->where('devise_cible_id', $devisePaiement->id)
            ->where('date_taux', '<=', $paie->date_fin)
            ->orderBy('date_taux', 'desc')
            ->first();

        if (!$tauxChange) {
            throw new \RuntimeException(
                "Aucun taux de change trouvé pour la paire "
                . $affectation->devise_id . " → " . $devisePaiement->id
                . " (date fin période : {$paie->date_fin})"
            );
        }

        $bulletin->taux_change = $tauxChange->taux;
        $bulletin->date_taux_change = $tauxChange->date_taux;
        $bulletin->source_taux_change = $tauxChange->source;
        $bulletin->devise_source_id = $affectation->devise_id;
        $bulletin->devise_paiement_id = $devisePaiement->id;
    }
}
