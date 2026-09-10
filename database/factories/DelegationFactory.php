<?php

namespace Database\Factories;

use App\Models\Delegation;
use App\Models\Employe;
use App\Models\Devise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Delegation>
 */
class DelegationFactory extends Factory
{
    protected $model = Delegation::class;

    public function definition(): array
    {
        return [
            'employe_id' => Employe::factory(),
            'beneficiaire' => $this->faker->company,
            'montant' => $this->faker->randomFloat(2, 1000, 100000),
            'devise_id' => Devise::factory(),
            'date_debut' => $this->faker->date(),
            'date_fin' => $this->faker->optional()->date(),
            'frequence' => $this->faker->randomElement(['mensuelle', 'ponctuelle']),
            'statut' => 'actif',
        ];
    }
}