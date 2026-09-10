<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Smoke-test end-to-end contre la base PostgreSQL réelle (ne la modifie pas :
 * tout est roulé dans une transaction). Reproduit fidèlement le flux navigateur
 * (cookie CSRF chiffré -> login -> endpoints authentifiés), avec Origin valorisé
 * pour activer la pile "stateful" de Sanctum (session + CSRF).
 */
class SmokePgsqlTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('Origin', 'http://127.0.0.1:8000');
    }

    private function xsrfToken(): string
    {
        return $this->get('/sanctum/csrf-cookie')
            ->getCookie('XSRF-TOKEN', false)
            ->getValue();
    }

    public function test_flux_navigateur_complet_contre_pgsql(): void
    {
        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ], ['X-XSRF-TOKEN' => $this->xsrfToken()])
            ->assertOk();

        $this->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.user.email', 'admin@example.com');

        $this->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['employes_actifs', 'affectations_actives', 'navires', 'paies_par_statut', 'bulletins'],
            ]);

        $this->getJson('/api/v1/employes')->assertOk();
        $this->getJson('/api/v1/pays')->assertOk();
        $this->getJson('/api/v1/devises')->assertOk();
        $this->getJson('/api/v1/paies')->assertOk();

        $this->postJson('/api/v1/auth/logout', [], [
            'X-XSRF-TOKEN' => $this->xsrfToken(),
        ])->assertOk();

        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    }
}