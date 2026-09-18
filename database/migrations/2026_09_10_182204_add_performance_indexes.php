<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avances', function (Blueprint $table) {
            $table->index('employe_id', 'avances_employe_idx');
        });

        Schema::table('delegations', function (Blueprint $table) {
            $table->index('employe_id', 'delegations_employe_idx');
        });

        Schema::table('paies', function (Blueprint $table) {
            $table->index('statut', 'paies_statut_idx');
        });

        Schema::table('bulletins_paie', function (Blueprint $table) {
            $table->index('navire_id', 'bulletins_navire_idx');
        });

        Schema::table('affectations_marin', function (Blueprint $table) {
            $table->index(['date_embt', 'date_debt', 'statut'], 'affectations_periode_idx');
        });
    }

    public function down(): void
    {
        Schema::table('avances', fn(Blueprint $t) => $t->dropIndex('avances_employe_idx'));
        Schema::table('delegations', fn(Blueprint $t) => $t->dropIndex('delegations_employe_idx'));
        Schema::table('paies', fn(Blueprint $t) => $t->dropIndex('paies_statut_idx'));
        Schema::table('bulletins_paie', fn(Blueprint $t) => $t->dropIndex('bulletins_navire_idx'));
        Schema::table('affectations_marin', fn(Blueprint $t) => $t->dropIndex('affectations_periode_idx'));
    }
};
