<?php

namespace Database\Factories;

use App\Models\RemboursementAvance;
use App\Models\Avance;
use App\Models\BulletinPaie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RemboursementAvance>
 */
class RemboursementAvanceFactory extends Factory
{
    protected $model = RemboursementAvance::class;

    public function definition(): array
    {
        return [
            'avance_id' => Avance::factory(),
            'bulletin_id' => BulletinPaie::factory(),
            'montant' => $this->faker->randomFloat(2, 1000, 50000),
        ];
    }
}