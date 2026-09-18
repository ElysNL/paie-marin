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
        Schema::table('bulletins_paie', function (Blueprint $table) {
            $table->foreignId('devise_source_id')->nullable()->change();
            $table->foreignId('devise_paiement_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulletins_paie', function (Blueprint $table) {
            $table->foreignId('devise_source_id')->nullable(false)->change();
            $table->foreignId('devise_paiement_id')->nullable(false)->change();
        });
    }
};
