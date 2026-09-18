<?php

namespace Database\Factories;

use App\Models\Fonction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fonction>
 */
class FonctionFactory extends Factory
{
    protected $model = Fonction::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'libelle' => $this->faker->unique()->randomElement([
                'Capitaine', 'Mécanicien', 'Second', 'Matelot', 'Électricien',
                'Cuisinier', 'Lieutenant', 'Bosco', 'Gréviste', 'Ouvrier',
            ]),
            'description' => $this->faker->sentence,
            'actif' => true,
        ];
    }
}
