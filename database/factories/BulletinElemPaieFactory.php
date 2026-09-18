<?php

namespace Database\Factories;

use App\Models\BulletinElemPaie;
use App\Models\BulletinPaie;
use App\Models\ElemPaie;
use App\Models\Devise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BulletinElemPaie>
 */
class BulletinElemPaieFactory extends Factory
{
    protected $model = BulletinElemPaie::class;

    public function definition(): array
    {
        return [
            'bulletin_id' => BulletinPaie::factory(),
            'elem_paie_id' => ElemPaie::factory(),
            'quantite' => $this->faker->optional()->randomFloat(2, 1, 31),
            'unite' => $this->faker->optional()->randomElement(['j', 'h', '%']),
            'base' => $this->faker->optional()->randomFloat(2, 0, 5000000),
            'taux' => $this->faker->optional()->randomFloat(2, 0, 100),
            'montant' => $this->faker->randomFloat(2, 1000, 5000000),
            'devise_id' => Devise::factory(),
            'description' => $this->faker->optional()->sentence,
            'ordre' => $this->faker->numberBetween(1, 20),
        ];
    }
}