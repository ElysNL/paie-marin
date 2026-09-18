<?php

namespace Tests\Feature\Api\V1;

use App\Models\Navire;
use App\Models\Paie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_dashboard_requiert_authentification()
    {
        $this->getJson('/api/v1/dashboard')->assertUnauthorized();
    }

    public function test_le_dashboard_retourne_les_statistiques()
    {
        $user = User::factory()->create();

        Navire::factory()->create();
        Paie::factory()->create(['statut' => 'brouillon']);
        Paie::factory()->create(['statut' => 'calcule']);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/dashboard');

        $response->assertOk()
            ->assertJsonPath('data.navires', 1)
            ->assertJsonPath('data.paies_par_statut.brouillon', 1)
            ->assertJsonPath('data.paies_par_statut.calcule', 1)
            ->assertJsonCount(2, 'data.dernieres_paies');
    }
}