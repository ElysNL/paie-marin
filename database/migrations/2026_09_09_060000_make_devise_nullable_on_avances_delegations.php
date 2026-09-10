<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations : la devise est facultative sur une avance/délégation,
     * la devise de paiement par défaut (MGA) s'applique.
     */
    public function up(): void
    {
        Schema::table('avances', function (Blueprint $table) {
            $table->foreignId('devise_id')->nullable()->change();
        });

        Schema::table('delegations', function (Blueprint $table) {
            $table->foreignId('devise_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avances', function (Blueprint $table) {
            $table->foreignId('devise_id')->nullable(false)->change();
        });

        Schema::table('delegations', function (Blueprint $table) {
            $table->foreignId('devise_id')->nullable(false)->change();
        });
    }
};