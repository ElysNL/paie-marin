<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Simule l'application frontend (même domaine) pour activer l'état
        // "stateful" de Sanctum et donc la session + CSRF.
        $this->withHeader('Origin', 'http://localhost:8000');
    }

    private function creerUser(): User
    {
        return User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
    }

    /**
     * Récupère un cookie CSRF frais, comme le fait le navigateur via la
     * route /sanctum/csrf-cookie avant la connexion.
     *
     * Le navigateur réenvoie la VALEUR CHIFFRÉE du cookie XSRF-TOKEN dans le
     * header X-XSRF-TOKEN. Or Laravel déchiffre ce header (PreventRequestForgery
     * -> getTokenFromRequest). On renvoie donc la valeur chiffrée du cookie (et
     * non le jeton brut) pour que la validation CSRF aboutisse.
     */
    private function xsrfToken(): string
    {
        return $this->get('/sanctum/csrf-cookie')
            ->getCookie('XSRF-TOKEN', false)
            ->getValue();
    }

    private function login(): void
    {
        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ], ['X-XSRF-TOKEN' => $this->xsrfToken()])->assertOk();
    }

    public function test_un_utilisateur_peut_se_connecter()
    {
        $this->creerUser();
        $this->login();

        $this->assertAuthenticated();
    }

    public function test_rejecte_les_mauvais_identifiants()
    {
        $this->creerUser();

        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'mauvais-mot-de-passe',
        ], ['X-XSRF-TOKEN' => $this->xsrfToken()])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $this->assertGuest();
    }

    public function test_me_requiert_une_session()
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_me_retourne_l_utilisateur_connecte()
    {
        $this->creerUser();
        $this->login();

        $this->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.user.email', 'admin@example.com');
    }

    public function test_un_utilisateur_peut_se_deconnecter()
    {
        $this->creerUser();
        $this->login();

        // Le logout est un POST soumis au CSRF : on renvoie un token frais
        // (la session a été régénérée au login, le jeton a donc changé).
        $this->postJson('/api/v1/auth/logout', [], [
            'X-XSRF-TOKEN' => $this->xsrfToken(),
        ])->assertOk()
            ->assertJsonPath('message', 'Déconnecté avec succès.');

        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    }
}