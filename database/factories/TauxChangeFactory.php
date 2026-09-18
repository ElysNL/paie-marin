<?php

namespace Database\Factories;

use App\Models\TauxChange;
use App\Models\Devise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TauxChange>
 */
class TauxChangeFactory extends Factory
{
    protected $model = TauxChange::class;

    public function definition(): array
    {
        return [
            'devise_source_id' => Devise::factory(),
            'devise_cible_id' => Devise::factory(),
            'taux' => $this->faker->randomFloat(6, 100, 5000),
            'date_taux' => $this->faker->date(),
            'source' => $this->faker->randomElement(['BCM', 'Banque', 'Manuel']),
        ];
    }
}
