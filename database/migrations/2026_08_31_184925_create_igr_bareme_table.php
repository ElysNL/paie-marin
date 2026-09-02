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
        Schema::create('igr_parametres', function (Blueprint $table) {
            $table->id();
            $table->string('libelle', 100);
            $table->decimal('tranche_inf', 12, 2);
            $table->decimal('tranche_sup', 12, 2)->nullable();
            $table->decimal('taux_igr', 8, 4); // en %
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('igr_bareme');
    }
};
