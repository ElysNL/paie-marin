<?php

namespace Database\Factories;

use App\Models\BulletinPaie;
use App\Models\Paie;
use App\Models\Employe;
use App\Models\AffectationMarin;
use App\Models\Navire;
use App\Models\Devise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BulletinPaie>
 */
class BulletinPaieFactory extends Factory
{
    protected $model = BulletinPaie::class;

    public function definition(): array
    {
        return [
            'paie_id' => Paie::factory(),
            'employe_id' => Employe::factory(),
            'affectation_id' => AffectationMarin::factory(),
            'navire_id' => Navire::factory(),
            'devise_source_id' => Devise::factory(),
            'devise_paiement_id' => Devise::factory(),
            'taux_change' => $this->faker->optional()->randomFloat(6, 1, 5000),
            'date_taux_change' => $this->faker->optional()->date(),
            'source_taux_change' => $this->faker->optional()->word,
            'total_jours' => $this->faker->numberBetween(20, 31),
            'total_gains' => $this->faker->randomFloat(2, 100000, 5000000),
            'total_brut' => $this->faker->randomFloat(2, 100000, 5000000),
            'total_cotisations_salariales' => $this->faker->randomFloat(2, 0, 500000),
            'total_retenues' => $this->faker->randomFloat(2, 0, 500000),
            'total_cotisations_patronales' => $this->faker->randomFloat(2, 0, 500000),
            'net_a_payer' => $this->faker->randomFloat(2, 50000, 5000000),
            'cout_total_employeur' => $this->faker->randomFloat(2, 100000, 6000000),
            'statut' => 'calcule',
        ];
    }
}