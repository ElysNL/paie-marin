<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ElemPaie;

class ElemPaieSeeder extends Seeder
{
    public function run()
    {
        $elements = [
            ['code' => 'SAL_BASE', 'libelle' => 'Salaire de base', 'type' => 'GAIN', 'est_variable' => false, 'imposable' => true, 'cotisable' => false, 'ordre' => 10],
            ['code' => 'CONGE', 'libelle' => 'Congé payé', 'type' => 'GAIN', 'est_variable' => false, 'imposable' => true, 'cotisable' => false, 'ordre' => 20],
            ['code' => 'FERIE', 'libelle' => 'Jour férié', 'type' => 'GAIN', 'est_variable' => false, 'imposable' => true, 'cotisable' => false, 'ordre' => 30],
            ['code' => 'HS', 'libelle' => 'Heures supplémentaires', 'type' => 'GAIN', 'est_variable' => true, 'imposable' => true, 'cotisable' => false, 'ordre' => 40],
            ['code' => 'PRIME_NAVIGATION', 'libelle' => 'Prime de navigation', 'type' => 'GAIN', 'est_variable' => true, 'imposable' => true, 'cotisable' => false, 'ordre' => 50],
            ['code' => 'PRIME_RISQUE', 'libelle' => 'Prime de risque', 'type' => 'GAIN', 'est_variable' => true, 'imposable' => true, 'cotisable' => false, 'ordre' => 60],
            ['code' => 'PRIME_ANCIENNETE', 'libelle' => 'Prime d’ancienneté', 'type' => 'GAIN', 'est_variable' => false, 'imposable' => true, 'cotisable' => false, 'ordre' => 70],
            ['code' => 'BONUS', 'libelle' => 'Bonus', 'type' => 'GAIN', 'est_variable' => true, 'imposable' => true, 'cotisable' => false, 'ordre' => 80],
            ['code' => 'CNAPS_SAL', 'libelle' => 'CNAPS (salarié)', 'type' => 'COTISATION', 'est_variable' => false, 'imposable' => false, 'cotisable' => false, 'ordre' => 100],
            ['code' => 'SMIDS_SAL', 'libelle' => 'SMIDS (salarié)', 'type' => 'COTISATION', 'est_variable' => false, 'imposable' => false, 'cotisable' => false, 'ordre' => 110],
            ['code' => 'IGR', 'libelle' => 'Impôt sur le revenu', 'type' => 'RETENUE', 'est_variable' => false, 'imposable' => false, 'cotisable' => false, 'ordre' => 120],
            ['code' => 'AVANCE', 'libelle' => 'Remboursement avance', 'type' => 'RETENUE', 'est_variable' => false, 'imposable' => false, 'cotisable' => false, 'ordre' => 130],
            ['code' => 'DELEGATION', 'libelle' => 'Délégation', 'type' => 'RETENUE', 'est_variable' => false, 'imposable' => false, 'cotisable' => false, 'ordre' => 140],
            ['code' => 'AUTRE_RETENUE', 'libelle' => 'Autre retenue', 'type' => 'RETENUE', 'est_variable' => true, 'imposable' => false, 'cotisable' => false, 'ordre' => 150],
        ];

        foreach ($elements as $elem) {
            ElemPaie::create($elem + ['actif' => true]);
        }
    }
}
