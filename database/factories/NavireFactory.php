<?php

namespace Database\Factories;

use App\Models\Navire;
use App\Models\Armateur;
use App\Models\Compagnie;
use App\Models\Pays;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Navire>
 */
class NavireFactory extends Factory
{
    protected $model = Navire::class;

    public function definition(): array
    {
        return [
            'armateur_id' => Armateur::factory(),
            'compagnie_id' => Compagnie::factory(),
            'code' => strtoupper($this->faker->unique()->lexify('NAV???')),
            'nom' => 'MV ' . $this->faker->word . ' ' . $this->faker->word,
            'immatriculation' => strtoupper($this->faker->unique()->bothify('###??')),
            'pavillon_id' => Pays::factory(),
            'type' => $this->faker->randomElement(['Cargo', 'Pétrolier', 'Passager', 'Pêche', 'Remorqueur']),
            'actif' => true,
        ];
    }
}
