<?php

namespace Database\Factories;

use App\Models\SpecificiteArmateur;
use App\Models\Armateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SpecificiteArmateur>
 */
class SpecificiteArmateurFactory extends Factory
{
    protected $model = SpecificiteArmateur::class;

    public function definition(): array
    {
        return [
            'armateur_id' => Armateur::factory(),
            'cle' => $this->faker->word,
            'valeur' => $this->faker->word,
        ];
    }
}
