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
        Schema::create('paies', function (Blueprint $table) {
            $table->id();
            $table->string('num_paie', 20)->unique();
            $table->string('libelle', 100);
            $table->string('periode', 20); // ex: "Mensuelle", "Quinzaine"
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['brouillon', 'calcule', 'controle', 'valide', 'cloture'])->default('brouillon');
            $table->date('date_validation')->nullable();
            $table->date('date_cloture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodes_paie');
    }
};
