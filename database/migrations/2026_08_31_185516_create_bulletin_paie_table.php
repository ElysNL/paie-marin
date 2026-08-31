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
        Schema::create('bulletins_paie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paie_id')->constrained('paies')->onDelete('cascade');
            $table->foreignId('employe_id')->constrained('employes')->restrictOnDelete();
            $table->foreignId('affectation_id')->constrained('affectations_marin')->restrictOnDelete();
            $table->foreignId('navire_id')->constrained('navires')->restrictOnDelete();
            $table->foreignId('devise_source_id')->constrained('devises')->restrictOnDelete(); // devise du contrat
            $table->foreignId('devise_paiement_id')->constrained('devises')->restrictOnDelete(); // devise de paiement
            $table->decimal('taux_change', 14, 6)->nullable();
            $table->date('date_taux_change')->nullable();
            $table->string('source_taux_change', 100)->nullable();
            $table->decimal('total_jours', 8, 2)->default(0);
            $table->decimal('total_gains', 12, 2)->default(0);
            $table->decimal('total_brut', 12, 2)->default(0);
            $table->decimal('total_cotisations_salariales', 12, 2)->default(0);
            $table->decimal('total_retenues', 12, 2)->default(0);
            $table->decimal('total_cotisations_patronales', 12, 2)->default(0);
            $table->decimal('net_a_payer', 12, 2)->default(0);
            $table->decimal('cout_total_employeur', 12, 2)->default(0);
            $table->enum('statut', ['brouillon', 'calcule', 'valide', 'cloture'])->default('brouillon');
            $table->timestamps();

            $table->index(['paie_id', 'employe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletin_paie');
    }
};
