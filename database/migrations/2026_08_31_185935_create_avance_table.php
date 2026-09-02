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
        Schema::create('avances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->date('date_avance');
            $table->decimal('montant', 12, 2);
            $table->foreignId('devise_id')->constrained('devises')->restrictOnDelete();
            $table->string('motif')->nullable();
            $table->enum('statut', ['en_cours', 'remboursee', 'annulee'])->default('en_cours');
            $table->decimal('solde', 12, 2)->nullable(); // peut être calculé
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avance');
    }
};
