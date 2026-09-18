<?php

namespace Database\Factories;

use App\Models\Pays;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pays>
 */
class PaysFactory extends Factory
{
    protected $model = Pays::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'nom' => $this->faker->country,
            'nationalite' => $this->faker->word,
            'actif' => true,
        ];
    }

    public function malgache(): static
    {
        return $this->state(fn () => [
            'code' => 'MDG',
            'nom' => 'Madagascar',
            'nationalite' => 'Malgache',
        ]);
    }
}
