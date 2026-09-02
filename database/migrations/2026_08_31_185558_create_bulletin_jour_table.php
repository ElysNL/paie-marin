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
        Schema::create('bulletins_jour', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulletin_id')->constrained('bulletins_paie')->onDelete('cascade');
            $table->date('date');
            $table->enum('type_jour', ['NORMAL', 'FERIE', 'CONGE', 'REPOS', 'ABSENCE', 'MALADIE', 'AUTRE']);
            $table->decimal('nombre', 8, 2)->default(1);
            $table->decimal('taux', 12, 2)->nullable();
            $table->decimal('montant', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletin_jour');
    }
};
