<?php

namespace Database\Factories;

use App\Models\Agence;
use App\Models\Banque;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agence>
 */
class AgenceFactory extends Factory
{
    protected $model = Agence::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('????')),
            'nom' => $this->faker->company,
            'banque_id' => Banque::factory(),
            'adresse' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
            'actif' => true,
        ];
    }
}
