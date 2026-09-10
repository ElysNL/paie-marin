<?php

namespace Database\Factories;

use App\Models\ContratArmateur;
use App\Models\Armateur;
use App\Models\Devise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContratArmateur>
 */
class ContratArmateurFactory extends Factory
{
    protected $model = ContratArmateur::class;

    public function definition(): array
    {
        return [
            'armateur_id' => Armateur::factory(),
            'code' => strtoupper($this->faker->unique()->lexify('CTR???')),
            'libelle' => $this->faker->sentence(3),
            'devise_id' => Devise::factory(),
            'date_debut' => $this->faker->date(),
            'date_fin' => $this->faker->optional()->date(),
            'taux_base' => $this->faker->randomFloat(2, 50, 500),
            'conditions' => $this->faker->optional()->paragraph,
            'actif' => true,
        ];
    }
}
