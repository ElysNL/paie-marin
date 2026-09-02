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
        Schema::create('taux_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devise_source_id')->constrained('devises')->restrictOnDelete();
            $table->foreignId('devise_cible_id')->constrained('devises')->restrictOnDelete();
            $table->decimal('taux', 14, 6);
            $table->date('date_taux');
            $table->timestamps();

            $table->unique(['devise_source_id', 'devise_cible_id', 'date_taux']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taux_change');
    }
};
