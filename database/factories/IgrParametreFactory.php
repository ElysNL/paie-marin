<?php

namespace Database\Factories;

use App\Models\IgrParametre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IgrParametre>
 */
class IgrParametreFactory extends Factory
{
    protected $model = IgrParametre::class;

    public function definition(): array
    {
        $inf = $this->faker->numberBetween(0, 800000);
        $sup = $inf + $this->faker->numberBetween(50000, 200000);
        return [
            'libelle' => 'Tranche de ' . $inf . ' à ' . $sup,
            'tranche_inf' => $inf,
            'tranche_sup' => $sup,
            'taux_igr' => $this->faker->randomElement([0, 5, 10, 15, 20]),
            'date_debut' => $this->faker->date(),
            'date_fin' => null,
        ];
    }
}
