<?php

namespace Database\Factories;

use App\Models\Armateur;
use App\Models\Pays;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Armateur>
 */
class ArmateurFactory extends Factory
{
    protected $model = Armateur::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('ARM???')),
            'nom' => $this->faker->company,
            'adresse' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'pays_id' => Pays::factory(),
            'actif' => true,
        ];
    }
}
