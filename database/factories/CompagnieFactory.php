<?php

namespace Database\Factories;

use App\Models\Compagnie;
use App\Models\Pays;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Compagnie>
 */
class CompagnieFactory extends Factory
{
    protected $model = Compagnie::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('????')),
            'nom' => $this->faker->company,
            'pays_id' => Pays::factory(),
            'actif' => true,
        ];
    }
}
