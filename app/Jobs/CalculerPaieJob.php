<?php

namespace App\Jobs;

use App\Models\Paie;
use App\Models\AffectationMarin;
use App\Services\CalculateurDePaie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CalculerPaieJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 300;

    public function __construct(
        public int $paieId,
        public ?int $navireId = null,
        public ?int $employeId = null,
    ) {}

    public function handle(CalculateurDePaie $calculator): void
    {
        $paie = Paie::lockForUpdate()->find($this->paieId);

        if (!$paie || $paie->statut !== 'brouillon') {
            $this->fail(new \Exception('Paie introuvable ou déjà calculée.'));
            return;
        }

        if ($paie->statut_calcul === 'en_cours') {
            $this->fail(new \Exception('Un calcul est déjà en cours pour cette paie.'));
            return;
        }

        $paie->update(['statut_calcul' => 'en_cours']);

        try {
            $query = AffectationMarin::pourPeriode($paie->date_debut, $paie->date_fin)
                ->actif()
                ->with(['employe', 'navire', 'fonction']);

            if ($this->navireId) {
                $query->where('navire_id', $this->navireId);
            }

            if ($this->employeId) {
                $query->where('employe_id', $this->employeId);
            }

            $affectations = $query->get();

            if ($affectations->isEmpty()) {
                $paie->update([
                    'statut_calcul' => 'termine',
                    'resultat_calcul' => [
                        'nb_bulletins' => 0,
                        'navire_id' => $this->navireId,
                        'employe_id' => $this->employeId,
                        'message' => 'Aucune affectation éligible.',
                    ],
                ]);
                return;
            }

            // Supprimer les anciens bulletins (recalcul)
            $bulletinQuery = $paie->bulletins();
            if ($this->navireId) {
                $bulletinQuery->where('navire_id', $this->navireId);
            }
            if ($this->employeId) {
                $bulletinQuery->where('employe_id', $this->employeId);
            }
            $bulletinQuery->delete();

            // Calculer chaque bulletin
            $nbBulletins = 0;
            foreach ($affectations as $affectation) {
                $calculator->calculateBulletin($paie, $affectation);
                $nbBulletins++;
            }

            $paie->update([
                'statut_calcul' => 'termine',
                'statut' => 'calcule',
                'version' => $paie->version + 1,
                'resultat_calcul' => [
                    'nb_bulletins' => $nbBulletins,
                    'navire_id' => $this->navireId,
                    'employe_id' => $this->employeId,
                ],
            ]);

        } catch (\Exception $e) {
            $paie->update([
                'statut_calcul' => 'erreur',
                'resultat_calcul' => [
                    'erreur' => $e->getMessage(),
                    'navire_id' => $this->navireId,
                    'employe_id' => $this->employeId,
                ],
            ]);
            throw $e;
        }
    }
}
