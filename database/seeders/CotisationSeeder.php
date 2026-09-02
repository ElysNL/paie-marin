<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cotisation;

class CotisationSeeder extends Seeder
{
    public function run()
    {
        // CNAPS (salarié 1%, patronal 5% par exemple)
        Cotisation::create([
            'code' => 'CNAPS',
            'libelle' => 'Caisse Nationale de Prévoyance Sociale',
            'organisme' => 'CNAPS',
            'taux_salarial' => 1.0000,
            'plafond_salarial' => 2000000.00, // plafond mensuel en MGA
            'taux_patronal' => 5.0000,
            'plafond_patronal' => 2000000.00,
            'date_debut' => '2026-01-01',
            'date_fin' => null,
            'actif' => true,
        ]);

        // SMIDS (salarié 1.5%, patronal 3.5%)
        Cotisation::create([
            'code' => 'SMIDS',
            'libelle' => 'Sécurité Malgache',
            'organisme' => 'SMIDS',
            'taux_salarial' => 1.5000,
            'plafond_salarial' => 5000000.00,
            'taux_patronal' => 3.5000,
            'plafond_patronal' => 5000000.00,
            'date_debut' => '2026-01-01',
            'date_fin' => null,
            'actif' => true,
        ]);
    }
}
