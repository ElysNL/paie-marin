<?php

namespace Database\Factories;

use App\Models\Devise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Devise>
 */
class DeviseFactory extends Factory
{
    protected $model = Devise::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'libelle' => $this->faker->sentence(3),
            'symbole' => $this->faker->randomElement(['$', '€', '£', 'Ar']),
            'nb_decimales' => 2,
            'actif' => true,
        ];
    }

    public function mga(): static
    {
        return $this->state(fn () => [
            'code' => 'MGA',
            'libelle' => 'Ariary malgache',
            'symbole' => 'Ar',
            'nb_decimales' => 0,
        ]);
    }

    public function usd(): static
    {
        return $this->state(fn () => [
            'code' => 'USD',
            'libelle' => 'Dollar américain',
            'symbole' => '$',
            'nb_decimales' => 2,
        ]);
    }
}
