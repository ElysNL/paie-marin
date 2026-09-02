<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Devise, Pays, Fonction, Armateur, Compagnie, Navire, Employe, AffectationMarin, ContratArmateur};

class TestSeeder extends Seeder
{
    public function run()
    {
        // Devises
        $usd = Devise::create(['code' => 'USD', 'libelle' => 'Dollar américain', 'symbole' => '$', 'actif' => true]);
        $mga = Devise::create(['code' => 'MGA', 'libelle' => 'Ariary malgache', 'symbole' => 'Ar', 'actif' => true]);
        $eur = Devise::create(['code' => 'EUR', 'libelle' => 'Euro', 'symbole' => '€', 'actif' => true]);

        // Pays
        $mg = Pays::create(['code' => 'MDG', 'nom' => 'Madagascar', 'nationalite' => 'Malgache', 'actif' => true]);
        $fr = Pays::create(['code' => 'FRA', 'nom' => 'France', 'nationalite' => 'Française', 'actif' => true]);

        // Fonctions
        $fonction1 = Fonction::create(['code' => 'MEC', 'libelle' => 'Mécanicien', 'actif' => true]);
        $fonction2 = Fonction::create(['code' => 'CAP', 'libelle' => 'Capitaine', 'actif' => true]);

        // Classification (optionnel)
        // Classification::create(['code' => 'CL1', 'libelle' => 'Classe 1', 'actif' => true]);

        // Armateur
        $arm = Armateur::create([
            'code' => 'ARM001',
            'nom' => 'Armateur Océan',
            'pays_id' => $mg->id,
            'actif' => true
        ]);

        // Compagnie
        $comp = Compagnie::create([
            'code' => 'C001',
            'nom' => 'Compagnie Maritime',
            'pays_id' => $mg->id,
            'actif' => true
        ]);

        // Contrat armateur
        $contrat = ContratArmateur::create([
            'armateur_id' => $arm->id,
            'code' => 'CTR001',
            'libelle' => 'Contrat standard',
            'devise_id' => $usd->id,
            'date_debut' => '2026-01-01',
            'taux_base' => 100.00,
            'actif' => true
        ]);

        // Navire
        $navire = Navire::create([
            'armateur_id' => $arm->id,
            'compagnie_id' => $comp->id,
            'code' => 'NAV001',
            'nom' => 'MV OCEAN STAR',
            'immatriculation' => 'ABC123',
            'pavillon_id' => $mg->id,
            'type' => 'Cargo',
            'actif' => true
        ]);

        // Marin
        $employer = Employe::create([
            'matricule' => 'M001',
            'nom' => 'DUPONT',
            'prenom' => 'Jean',
            'nationalite_id' => $mg->id,
            'date_embauche' => '2025-01-01',
            'actif' => true
        ]);

        // Affectation
        AffectationMarin::create([
            'employe_id' => $employer->id,
            'navire_id' => $navire->id,
            'fonction_id' => $fonction1->id,
            'contrat_armateur_id' => $contrat->id,
            'date_embt' => '2026-08-01',
            'date_debt' => '2026-08-31',
            'taux_journalier' => 100.00,
            'devise_id' => $usd->id,
            'statut' => 'actif'
        ]);
    }
}
