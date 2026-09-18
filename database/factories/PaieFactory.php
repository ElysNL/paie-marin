<?php

namespace Database\Factories;

use App\Models\Paie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Paie>
 */
class PaieFactory extends Factory
{
    protected $model = Paie::class;

    public function definition(): array
    {
        $debut = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');
        $fin = date('Y-m-t', strtotime($debut));
        return [
            'num_paie' => strtoupper($this->faker->unique()->bothify('PAIE-####')),
            'libelle' => 'Paie ' . $this->faker->monthName . ' ' . date('Y'),
            'periode' => 'Mensuelle',
            'date_debut' => $debut,
            'date_fin' => $fin,
            'statut' => 'brouillon',
            'date_validation' => null,
            'date_cloture' => null,
        ];
    }
}
