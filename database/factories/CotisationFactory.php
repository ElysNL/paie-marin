<?php

namespace Database\Factories;

use App\Models\Cotisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cotisation>
 */
class CotisationFactory extends Factory
{
    protected $model = Cotisation::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('????')),
            'libelle' => $this->faker->words(2, true),
            'organisme' => $this->faker->company,
            'taux_salarial' => $this->faker->randomFloat(2, 0, 10),
            'plafond_salarial' => $this->faker->optional()->randomNumber(7),
            'taux_patronal' => $this->faker->randomFloat(2, 0, 15),
            'plafond_patronal' => $this->faker->optional()->randomNumber(7),
            'date_debut' => $this->faker->date(),
            'date_fin' => null,
            'actif' => true,
        ];
    }
}
