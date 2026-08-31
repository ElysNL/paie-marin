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
        // Pour stocker des particularités propres à un armateur (ex: primes spécifiques)
        Schema::create('specificites_armateur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armateur_id')->constrained('armateurs')->onDelete('cascade');
            $table->string('cle', 50);
            $table->text('valeur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_specificite_armateur');
    }
};
