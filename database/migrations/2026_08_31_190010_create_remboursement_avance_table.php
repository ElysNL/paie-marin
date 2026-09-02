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
        Schema::create('remboursements_avance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avance_id')->constrained('avances')->onDelete('cascade');
            $table->foreignId('bulletin_id')->constrained('bulletins_paie')->onDelete('cascade');
            $table->decimal('montant', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remboursement_avance');
    }
};
