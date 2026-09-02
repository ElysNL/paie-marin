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
        Schema::create('navires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armateur_id')->constrained('armateurs')->onDelete('cascade');
            $table->foreignId('compagnie_id')->nullable()->constrained('compagnies')->nullOnDelete();
            $table->string('code', 20)->unique();
            $table->string('nom', 100);
            $table->string('immatriculation', 50)->nullable();
            $table->foreignId('pavillon_id')->nullable()->constrained('pays')->nullOnDelete(); // pays du pavillon
            $table->string('type', 50)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navire');
    }
};
