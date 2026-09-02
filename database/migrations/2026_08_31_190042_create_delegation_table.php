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
        Schema::create('delegations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->string('beneficiaire', 200);
            $table->decimal('montant', 12, 2);
            $table->foreignId('devise_id')->constrained('devises')->restrictOnDelete();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->string('frequence', 20)->default('mensuelle');
            $table->enum('statut', ['actif', 'termine', 'annule'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegation');
    }
};
