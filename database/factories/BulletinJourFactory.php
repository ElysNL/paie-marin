<?php

namespace Database\Factories;

use App\Models\BulletinJour;
use App\Models\BulletinPaie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BulletinJour>
 */
class BulletinJourFactory extends Factory
{
    protected $model = BulletinJour::class;

    public function definition(): array
    {
        return [
            'bulletin_id' => BulletinPaie::factory(),
            'date' => $this->faker->date(),
            'type_jour' => $this->faker->randomElement(['NORMAL', 'FERIE', 'CONGE', 'REPOS', 'ABSENCE', 'MALADIE', 'AUTRE']),
            'nombre' => 1,
            'taux' => $this->faker->optional()->randomFloat(2, 0, 200),
            'montant' => $this->faker->optional()->randomFloat(2, 0, 5000),
        ];
    }
}