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
        Schema::table('employes', function (Blueprint $table) {
            $table->string('num_lpm', 50)->nullable()->after('matricule');
            $table->string('num_cnaps', 50)->nullable()->after('num_lpm');
            $table->string('visa_contrat', 50)->nullable()->after('num_cnaps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employes', function (Blueprint $table) {
            $table->dropColumn(['num_lpm', 'num_cnaps', 'visa_contrat']);
        });
    }
};
