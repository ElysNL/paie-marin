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
        Schema::create('bulletins_elem_paie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulletin_id')->constrained('bulletins_paie')->onDelete('cascade');
            $table->foreignId('elem_paie_id')->constrained('elem_paies')->restrictOnDelete();
            $table->decimal('quantite', 12, 2)->nullable();
            $table->string('unite', 20)->nullable();
            $table->decimal('base', 12, 2)->nullable();
            $table->decimal('taux', 12, 2)->nullable();
            $table->decimal('montant', 12, 2)->default(0);
            $table->foreignId('devise_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletin_elem_paie');
    }
};
