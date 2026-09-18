<?php

namespace Database\Factories;

use App\Models\BulletinCotisation;
use App\Models\BulletinPaie;
use App\Models\Cotisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BulletinCotisation>
 */
class BulletinCotisationFactory extends Factory
{
    protected $model = BulletinCotisation::class;

    public function definition(): array
    {
        return [
            'bulletin_id' => BulletinPaie::factory(),
            'cotisation_id' => Cotisation::factory(),
            'assiette' => $this->faker->randomFloat(2, 100000, 5000000),
            'taux_salarial' => $this->faker->randomFloat(2, 0, 10),
            'montant_salarial' => $this->faker->randomFloat(2, 0, 500000),
            'taux_patronal' => $this->faker->randomFloat(2, 0, 15),
            'montant_patronal' => $this->faker->randomFloat(2, 0, 500000),
        ];
    }
}