<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\{Paie, AffectationMarin, Employer, Navire, Fonction, ContratArmateur, Devise};
use App\Services\CalculPaie;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalculPaieTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function il_calcule_un_bulletin_complet()
    {
        // Créer les données nécessaires
        $devise = Devise::factory()->create(['code' => 'USD']);
        $employer = Employer::factory()->create();
        $navire = Navire::factory()->create();
        $fonction = Fonction::factory()->create();
        $contrat = ContratArmateur::factory()->create(['devise_id' => $devise->id]);
        $affectation = AffectationMarin::factory()->create([
            'employer_id' => $employer->id,
            'navire_id' => $navire->id,
            'fonction_id' => $fonction->id,
            'contrat_armateur_id' => $contrat->id,
            'taux_journalier' => 100,
            'devise_id' => $devise->id,
        ]);

        $paie = Paie::factory()->create([
            'date_debut' => '2026-08-01',
            'date_fin'   => '2026-08-31',
        ]);

        $calculator = new CalculPaie();
        $bulletin = $calculator->calculateBulletin($paie, $affectation);

        $this->assertNotNull($bulletin);
        $this->assertEquals(31, $bulletin->total_jours);
        $this->assertEquals(3100 + 5000, $bulletin->total_brut); // 31*100 + 5000 prime
        $this->assertGreaterThan(0, $bulletin->net_a_payer);
        $this->assertEquals('calcule', $bulletin->statut);
    }
}
