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
        Schema::create('contrats_armateur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armateur_id')->constrained('armateurs')->onDelete('cascade');
            $table->string('code', 20)->unique();
            $table->string('libelle', 100);
            $table->foreignId('devise_id')->constrained('devises')->restrictOnDelete(); // devise contractuelle
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('taux_base', 10, 2)->nullable(); // taux journalier par défaut
            $table->text('conditions')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_armateur');
    }
};
