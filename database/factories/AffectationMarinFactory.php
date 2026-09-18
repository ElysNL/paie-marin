<?php

namespace Database\Factories;

use App\Models\AffectationMarin;
use App\Models\Employe;
use App\Models\Navire;
use App\Models\Fonction;
use App\Models\ContratArmateur;
use App\Models\Devise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AffectationMarin>
 */
class AffectationMarinFactory extends Factory
{
    protected $model = AffectationMarin::class;

    public function definition(): array
    {
        $dateEmbt = $this->faker->dateTimeBetween('-2 months', '-1 month');
        return [
            'employe_id' => Employe::factory(),
            'navire_id' => Navire::factory(),
            'fonction_id' => Fonction::factory(),
            'contrat_armateur_id' => ContratArmateur::factory(),
            'date_embt' => $dateEmbt,
            'date_debt' => $this->faker->optional()->dateTimeBetween($dateEmbt, '+2 months'),
            'taux_journalier' => $this->faker->randomFloat(2, 50, 200),
            'devise_id' => Devise::factory(),
            'statut' => 'actif',
        ];
    }
}
