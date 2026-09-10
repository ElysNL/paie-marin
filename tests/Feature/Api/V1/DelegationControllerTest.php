<?php

namespace Tests\Feature\Api\V1;

use App\Models\Delegation;
use App\Models\Employe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DelegationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cree_une_delegation_active()
    {
        $employe = Employe::factory()->create();

        $response = $this->postJson('/api/v1/delegations', [
            'employe_id' => $employe->id,
            'beneficiaire' => 'Banque BNI',
            'montant' => 200,
            'date_debut' => '2026-08-01',
            'date_fin' => '2026-12-31',
            'frequence' => 'mensuel',
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['beneficiaire' => 'Banque BNI', 'statut' => 'actif']);

        $this->assertDatabaseHas('delegations', [
            'employe_id' => $employe->id,
            'beneficiaire' => 'Banque BNI',
            'statut' => 'actif',
        ]);
    }

    public function test_valide_la_delegation()
    {
        $delegation = Delegation::factory()->create();

        $response = $this->putJson('/api/v1/delegations/' . $delegation->id, [
            'employe_id' => $delegation->employe_id,
            'beneficiaire' => 'Banque MCB',
            'montant' => 250,
            'date_debut' => '2026-08-01',
            'statut' => 'termine',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['beneficiaire' => 'Banque MCB', 'statut' => 'termine']);
    }
}