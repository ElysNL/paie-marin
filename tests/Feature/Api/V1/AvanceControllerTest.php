<?php

namespace Tests\Feature\Api\V1;

use App\Models\Avance;
use App\Models\Employe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cree_une_avance_avec_solde_complet()
    {
        $employe = Employe::factory()->create();

        $response = $this->postJson('/api/v1/avances', [
            'employe_id' => $employe->id,
            'date_avance' => '2026-08-10',
            'montant' => 500,
            'motif' => 'Avance sur salaire',
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['montant' => '500.00', 'solde' => '500.00', 'statut' => 'en_cours']);

        $this->assertDatabaseHas('avances', [
            'employe_id' => $employe->id,
            'montant' => 500,
            'solde' => 500,
        ]);
    }

    public function test_itere_les_avances_par_employe()
    {
        $employe = Employe::factory()->create();
        Avance::factory()->create(['employe_id' => $employe->id]);
        Avance::factory()->create(['employe_id' => $employe->id]);
        $autre = Employe::factory()->create();

        $response = $this->getJson('/api/v1/avances?employe_id=' . $employe->id);

        $response->assertOk()
            ->assertJsonCount(2, 'data');
        $this->assertSame($employe->id, $response->json('data.0.employe_id'));
    }
}