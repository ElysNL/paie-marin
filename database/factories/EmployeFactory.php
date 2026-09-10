<?php

namespace Database\Factories;

use App\Models\Employe;
use App\Models\Pays;
use App\Models\Banque;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employe>
 */
class EmployeFactory extends Factory
{
    protected $model = Employe::class;

    public function definition(): array
    {
        return [
            'matricule' => strtoupper($this->faker->unique()->lexify('M???')),
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'date_naissance' => $this->faker->date(),
            'lieu_naissance' => $this->faker->city,
            'nationalite_id' => Pays::factory(),
            'adresse' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'cin' => strtoupper($this->faker->unique()->bothify('#########')),
            'banque_id' => Banque::factory(),
            'compte_bancaire' => $this->faker->bankAccountNumber,
            'date_embauche' => $this->faker->date(),
            'nbre_charges' => $this->faker->numberBetween(0, 6),
            'actif' => true,
        ];
    }
}
