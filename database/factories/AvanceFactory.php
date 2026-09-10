<?php

namespace Database\Factories;

use App\Models\Avance;
use App\Models\Employe;
use App\Models\Devise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Avance>
 */
class AvanceFactory extends Factory
{
    protected $model = Avance::class;

    public function definition(): array
    {
        $montant = $this->faker->randomFloat(2, 10000, 500000);
        return [
            'employe_id' => Employe::factory(),
            'date_avance' => $this->faker->date(),
            'montant' => $montant,
            'devise_id' => Devise::factory(),
            'motif' => $this->faker->optional()->sentence,
            'statut' => 'en_cours',
            'solde' => $montant,
        ];
    }
}