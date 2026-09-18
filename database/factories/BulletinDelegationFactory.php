<?php

namespace Database\Factories;

use App\Models\BulletinDelegation;
use App\Models\BulletinPaie;
use App\Models\Delegation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BulletinDelegation>
 */
class BulletinDelegationFactory extends Factory
{
    protected $model = BulletinDelegation::class;

    public function definition(): array
    {
        return [
            'bulletin_id' => BulletinPaie::factory(),
            'delegation_id' => Delegation::factory(),
            'montant' => $this->faker->randomFloat(2, 1000, 100000),
        ];
    }
}