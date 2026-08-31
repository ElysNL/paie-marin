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
        Schema::create('elem_paies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('libelle', 100);
            $table->boolean('est_variable')->default(false);
            $table->enum('type', ['GAIN', 'RETENUE', 'COTISATION']);
            $table->string('modalite')->nullable(); // ex: fixe, pourcentage, quantite
            $table->boolean('affichee')->default(true);
            $table->boolean('imposable')->default(true);
            $table->boolean('cotisable')->default(false);
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('actif')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elem_paie');
    }
};
