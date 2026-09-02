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
        Schema::create('cotisations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('libelle', 100);
            $table->string('organisme', 100)->nullable();
            $table->decimal('taux_salarial', 8, 4)->nullable(); // en %
            $table->decimal('plafond_salarial', 12, 2)->nullable();
            $table->decimal('taux_patronal', 8, 4)->nullable();
            $table->decimal('plafond_patronal', 12, 2)->nullable();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotisation');
    }
};
