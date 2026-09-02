<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('affectations_marin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->foreignId('navire_id')->constrained('navires')->onDelete('cascade');
            $table->foreignId('fonction_id')->constrained('fonctions')->restrictOnDelete();
            $table->foreignId('contrat_armateur_id')->constrained('contrat_armateurs')->restrictOnDelete();
            $table->date('date_embt'); // embarquement
            $table->date('date_debt')->nullable(); // débarquement
            $table->decimal('taux_journalier', 12, 2);
            $table->foreignId('devise_id')->constrained('devises')->restrictOnDelete(); // devise du taux
            $table->enum('statut', ['actif', 'termine', 'annule'])->default('actif');
            $table->timestamps();

            // Contrainte : date_debt >= date_embt (vérification)
            $table->index(['employe_id', 'navire_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectation_marin');
    }
};
