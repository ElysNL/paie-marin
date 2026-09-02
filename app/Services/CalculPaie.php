<?php

namespace App\Services;

use App\Models\{Paie, AffectationMarin, BulletinPaie, BulletinJour, BulletinElemPaie, BulletinCotisation, ElemPaie, Cotisation, IgrParametre, TauxChange};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalculPaie
{
    public function calculateBulletin(Paie $paie, AffectationMarin $affectation): BulletinPaie
    {
        DB::beginTransaction();
        try {
            // 1. Créer le bulletin (initial)
            $bulletin = $this->createBulletin($paie, $affectation);

            // 2. Calculer les jours de la période (normaux, fériés, etc.)
            $jours = $this->calculateDays($paie, $affectation);
            $totalJours = array_sum(array_column($jours, 'nombre'));
            $bulletin->total_jours = $totalJours;

            // 3. Calculer le salaire de base
            $tauxJournalier = $affectation->taux_journalier;
            $salaireBase = $tauxJournalier * $totalJours;
            $this->addGain($bulletin, 'SAL_BASE', $salaireBase);

            // 4. Ajouter les autres gains (primes, bonus, etc.) - selon logique métier
            // (pour l'exemple, on ajoute une prime forfaitaire)
            $this->addGain($bulletin, 'PRIME_NAVIGATION', 5000); // exemple

            // 5. Calcul du BRUT = somme des gains (on récupère depuis les éléments)
            $brut = $bulletin->elements->where('elem_paie.type', 'GAIN')->sum('montant');
            $bulletin->total_gains = $brut;
            $bulletin->total_brut = $brut;

            // 6. Cotisations salariales
            $cotisationsSalariales = $this->calculateCotisations($bulletin, $brut, 'salarial');
            $bulletin->total_cotisations_salariales = $cotisationsSalariales;

            // 7. IGR
            $igr = $this->calculateIGR($brut);
            $this->addRetenue($bulletin, 'IGR', $igr);

            // 8. Avances (récupérer les avances en cours et affecter un remboursement)
            $avances = $this->getActiveAdvances($affectation->employer_id, $paie->date_debut, $paie->date_fin);
            $montantAvances = $avances->sum('solde') ?? 0; // ou un montant fixe par mois
            // Pour l'exemple, on prend le solde de la première avance
            if ($avances->isNotEmpty()) {
                $montantAvances = $avances->first()->solde;
                // Créer un remboursement (dans la table remboursements_avance)
                $this->addRetenue($bulletin, 'AVANCE', $montantAvances);
                // Enregistrer le remboursement
                $bulletin->remboursementsAvances()->create([
                    'avance_id' => $avances->first()->id,
                    'montant' => $montantAvances
                ]);
            }

            // 9. Délégations
            $delegations = $this->getActiveDelegations($affectation->employer_id, $paie->date_debut);
            foreach ($delegations as $del) {
                $this->addRetenue($bulletin, 'DELEGATION', $del->montant);
                $bulletin->delegations()->create([
                    'delegation_id' => $del->id,
                    'montant' => $del->montant
                ]);
            }

            // 10. Total retenues (cotisations salariales + IGR + avances + délégations)
            $totalRetenues = $bulletin->elements->where('elem_paie.type', 'RETENUE')->sum('montant')
                             + $bulletin->total_cotisations_salariales;
            $bulletin->total_retenues = $totalRetenues;

            // 11. NET = BRUT - total retenues
            $net = $brut - $totalRetenues;
            $bulletin->net_a_payer = $net;

            // 12. Cotisations patronales
            $cotisationsPatronales = $this->calculateCotisations($bulletin, $brut, 'patronal');
            $bulletin->total_cotisations_patronales = $cotisationsPatronales;
            $bulletin->cout_total_employeur = $brut + $cotisationsPatronales;

            // 13. Taux de change (figer celui de la date de fin de période)
            $tauxChange = TauxChange::where('devise_source_id', $affectation->devise_id)
                ->where('devise_cible_id', function ($query) use ($affectation) {
                    // on prend la devise de paiement = MGA par défaut (à paramétrer)
                    return $query->select('id')->from('devises')->where('code', 'MGA');
                })
                ->where('date_taux', '<=', $paie->date_fin)
                ->orderBy('date_taux', 'desc')
                ->first();

            if ($tauxChange) {
                $bulletin->taux_change = $tauxChange->taux;
                $bulletin->date_taux_change = $tauxChange->date_taux;
                $bulletin->source_taux_change = $tauxChange->source;
                $bulletin->devise_source_id = $affectation->devise_id;
                // Devise de paiement = MGA (à paramétrer)
                $bulletin->devise_paiement_id = Devise::where('code', 'MGA')->first()->id;
            }

            // 14. Sauvegarder le bulletin
            $bulletin->statut = 'calcule';
            $bulletin->save();

            DB::commit();
            return $bulletin;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Méthodes internes ...

    private function createBulletin($paie, $affectation)
    {
        return BulletinPaie::create([
            'paie_id' => $paie->id,
            'employer_id' => $affectation->employer_id,
            'affectation_id' => $affectation->id,
            'navire_id' => $affectation->navire_id,
            'devise_source_id' => $affectation->devise_id,
            // devise_paiement sera défini plus tard
            'statut' => 'brouillon'
        ]);
    }

    private function calculateDays($paie, $affectation)
    {
        // Logique pour calculer les jours de la période
        // Pour simplifier, on prend tous les jours entre date_debut et date_fin
        $days = [];
        $start = Carbon::parse($paie->date_debut);
        $end = Carbon::parse($paie->date_fin);
        $totalDays = $start->diffInDays($end) + 1;

        // On suppose que tous les jours sont "NORMAL" (à améliorer)
        $days[] = ['date' => $start->toDateString(), 'type' => 'NORMAL', 'nombre' => $totalDays, 'taux' => 0, 'montant' => 0];
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
            // autres champs (quantité, base, taux) si nécessaires
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
        // type = 'salarial' ou 'patronal'
        $cotisations = Cotisation::where('actif', true)->get();
        $total = 0;
        foreach ($cotisations as $cot) {
            $taux = $type === 'salarial' ? $cot->taux_salarial : $cot->taux_patronal;
            $plafond = $type === 'salarial' ? $cot->plafond_salarial : $cot->plafond_patronal;
            $base = min($assiette, $plafond ?? PHP_FLOAT_MAX);
            $montant = $base * ($taux / 100);
            $total += $montant;

            // Enregistrer dans bulletin_cotisation
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
            // On pourrait enregistrer séparément, mais ici on fait un seul insert
            $bulletin->cotisations()->create($data);
        }
        return $total;
    }

    private function calculateIGR($brut)
    {
        $tranches = IgrParametre::where('date_debut', '<=', Carbon::today())
            ->where(function ($q) {
                $q->whereNull('date_fin')->orWhere('date_fin', '>=', Carbon::today());
            })
            ->orderBy('tranche_inf')
            ->get();

        $impot = 0;
        foreach ($tranches as $tranche) {
            if ($brut <= $tranche->tranche_inf) continue;
            $base = min($brut, $tranche->tranche_sup ?? $brut) - $tranche->tranche_inf;
            $impot += $base * ($tranche->taux_igr / 100);
        }
        return $impot;
    }

    private function getActiveAdvances($employerId, $dateDebut, $dateFin)
    {
        return \App\Models\Avance::where('employer_id', $employerId)
            ->where('statut', 'en_cours')
            ->where('date_avance', '<=', $dateFin)
            ->get();
    }

    private function getActiveDelegations($employerId, $dateDebut)
    {
        return \App\Models\Delegation::where('employer_id', $employerId)
            ->where('statut', 'actif')
            ->where('date_debut', '<=', $dateDebut)
            ->where(function ($q) use ($dateDebut) {
                $q->whereNull('date_fin')->orWhere('date_fin', '>=', $dateDebut);
            })
            ->get();
    }
}
