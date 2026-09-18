<?php

namespace Tests\Unit\Services;

use App\Models\{Paie, AffectationMarin, Employe, Navire, Fonction, ContratArmateur, Devise, Avance};
use App\Services\CalculateurDePaie;
use Database\Seeders\{ElemPaieSeeder, CotisationSeeder, IgrParametreSeeder};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculateurDePaieTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Référentiels de paie (éléments, cotisations, tranches IGR)
        $this->seed(ElemPaieSeeder::class);
        $this->seed(CotisationSeeder::class);
        $this->seed(IgrParametreSeeder::class);
    }

    private function creerAffectation(array $attrs = []): AffectationMarin
    {
        $devise = Devise::factory()->create(['code' => 'USD']);
        $navire = Navire::factory()->create();
        $fonction = Fonction::factory()->create();
        $contrat = ContratArmateur::factory()->create(['devise_id' => $devise->id]);

        $nbreCharges = $attrs['nbre_charges'] ?? 0;
        unset($attrs['nbre_charges']);
        $employe = Employe::factory()->create(['nbre_charges' => $nbreCharges]);

        $affectation = AffectationMarin::factory()->create(array_merge([
            'employe_id' => $employe->id,
            'navire_id' => $navire->id,
            'fonction_id' => $fonction->id,
            'contrat_armateur_id' => $contrat->id,
            'date_embt' => '2026-08-01',
            'date_debt' => '2026-08-31',
            'taux_journalier' => 100,
            'devise_id' => $devise->id,
            'statut' => 'actif',
        ], $attrs));

        return $affectation;
    }

    private function creerPaie(): Paie
    {
        return Paie::factory()->create([
            'num_paie' => 'PAIE-2026-08',
            'libelle' => 'Paie Août 2026',
            'periode' => 'Mensuelle',
            'date_debut' => '2026-08-01',
            'date_fin' => '2026-08-31',
            'statut' => 'brouillon',
        ]);
    }

    public function test_il_calcule_un_bulletin_complet()
    {
        $paie = $this->creerPaie();
        $affectation = $this->creerAffectation();

        $calculator = new CalculateurDePaie();
        $bulletin = $calculator->calculateBulletin($paie, $affectation);

        $this->assertNotNull($bulletin);
        $this->assertEquals('calcule', $bulletin->statut);
        $this->assertEquals(31, $bulletin->total_jours);

        // SAL_BASE = 31 * 100 = 3100 ; PRIME_NAVIGATION = 5000
        $this->assertEquals(3100 + 5000, $bulletin->total_brut);

        // Cotisations salariales : CNAPS 1% + SMIDS 1,5% sur le brut
        $this->assertEqualsWithDelta(8100 * 0.025, (float) $bulletin->total_cotisations_salariales, 0.01);

        // Sans charge, abattement = 0 ; 8100 < tranche minimale IGR 350000 -> IGR = 0
        $this->assertEquals(0, $bulletin->elements->where('elem_paie.code', 'IGR')->sum('montant'));

        // NET à payer = BRUT - cotisations salariales (pas de retenue, pas d'avance)
        $this->assertEqualsWithDelta(8100 - 8100 * 0.025, (float) $bulletin->net_a_payer, 0.01);
        $this->assertGreaterThan(0, $bulletin->net_a_payer);
    }

    public function test_il_retire_l_abattement_sur_l_igr()
    {
        $paie = $this->creerPaie();
        // 2 charges => abattement = 2 * 2000 = 4000
        $affectation = $this->creerAffectation(['nbre_charges' => 2]);

        $calculator = new CalculateurDePaie();
        $bulletin = $calculator->calculateBulletin($paie, $affectation);

        $this->assertEquals(4000, $calculator->abattementPour($affectation->employe));
        // IGR reste à 0 car le brut (8100) est sous le seuil.
        $this->assertEquals(0, $bulletin->elements->where('elem_paie.code', 'IGR')->sum('montant'));
    }

    public function test_il_deduit_les_avances_du_net_a_payer()
    {
        $paie = $this->creerPaie();
        $affectation = $this->creerAffectation();

        // Avance de 500 USD, solde entier
        $devise = $affectation->devise;
        Avance::factory()->create([
            'employe_id' => $affectation->employe_id,
            'date_avance' => '2026-08-10',
            'montant' => 500,
            'devise_id' => $devise->id,
            'statut' => 'en_cours',
            'solde' => 500,
        ]);

        $calculator = new CalculateurDePaie();
        $bulletin = $calculator->calculateBulletin($paie, $affectation);

        $netSansAvance = 8100 - 8100 * 0.025; // 7897.5
        $this->assertEqualsWithDelta($netSansAvance - 500, (float) $bulletin->net_a_payer, 0.01);
        $this->assertTrue($bulletin->remboursementsAvances()->exists());
    }

    public function test_il_cree_un_bulletin_sans_avance_ni_delegation()
    {
        $paie = $this->creerPaie();
        $affectation = $this->creerAffectation();

        $calculator = new CalculateurDePaie();
        $bulletin = $calculator->calculateBulletin($paie, $affectation);

        $this->assertFalse($bulletin->remboursementsAvances()->exists());
        $this->assertCount(0, $bulletin->delegations);
    }
}