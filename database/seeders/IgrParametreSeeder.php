<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IgrParametre;

class IgrParametreSeeder extends Seeder
{
    public function run()
    {
        $tranches = [
            ['libelle' => 'Tranche 0%', 'tranche_inf' => 0, 'tranche_sup' => 350000, 'taux_igr' => 0],
            ['libelle' => 'Tranche 5%', 'tranche_inf' => 350001, 'tranche_sup' => 400000, 'taux_igr' => 5],
            ['libelle' => 'Tranche 10%', 'tranche_inf' => 400001, 'tranche_sup' => 500000, 'taux_igr' => 10],
            ['libelle' => 'Tranche 15%', 'tranche_inf' => 500001, 'tranche_sup' => 600000, 'taux_igr' => 15],
            ['libelle' => 'Tranche 20%', 'tranche_inf' => 600001, 'tranche_sup' => null, 'taux_igr' => 20],
        ];

        foreach ($tranches as $tranche) {
            IgrParametre::create(array_merge($tranche, [
                'date_debut' => '2026-01-01',
                'date_fin' => null,
            ]));
        }
    }
}
