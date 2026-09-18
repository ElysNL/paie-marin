<?php

namespace Database\Factories;

use App\Models\Banque;
use App\Models\Pays;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banque>
 */
class BanqueFactory extends Factory
{
    protected $model = Banque::class;

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
