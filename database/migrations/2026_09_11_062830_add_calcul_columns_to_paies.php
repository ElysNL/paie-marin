<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paies', function (Blueprint $table) {
            $table->integer('version')->default(0);
            $table->string('statut_calcul', 20)->nullable()->after('statut');
            $table->json('resultat_calcul')->nullable()->after('statut_calcul');
        });
    }

    public function down(): void
    {
        Schema::table('paies', function (Blueprint $table) {
            $table->dropColumn(['version', 'statut_calcul', 'resultat_calcul']);
        });
    }
};
