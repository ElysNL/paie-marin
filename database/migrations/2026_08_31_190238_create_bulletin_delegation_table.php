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
        Schema::create('bulletins_delegation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulletin_id')->constrained('bulletins_paie')->onDelete('cascade');
            $table->foreignId('delegation_id')->constrained('delegations')->onDelete('cascade');
            $table->decimal('montant', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletin_delegation');
    }
};
