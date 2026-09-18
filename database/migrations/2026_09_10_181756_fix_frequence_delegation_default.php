<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('delegations')->where('frequence', 'mensuelle')->update(['frequence' => 'mensuel']);
    }

    public function down(): void
    {
        DB::table('delegations')->where('frequence', 'mensuel')->update(['frequence' => 'mensuelle']);
    }
};
