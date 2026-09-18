<?php

namespace Database\Factories;

use App\Models\ElemPaie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ElemPaie>
 */
class ElemPaieFactory extends Factory
{
    protected $model = ElemPaie::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'libelle' => $this->faker->words(2, true),
            'est_variable' => $this->faker->boolean,
            'type' => $this->faker->randomElement(['GAIN', 'RETENUE', 'COTISATION']),
            'modalite' => $this->faker->optional()->word,
            'affichee' => true,
            'imposable' => true,
            'cotisable' => true,
            'ordre' => $this->faker->numberBetween(1, 20),
            'actif' => true,
        ];
    }
}
