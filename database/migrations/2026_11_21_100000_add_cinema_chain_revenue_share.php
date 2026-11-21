<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Part du revenu HT reversée à la chaîne : propre à chaque chaîne, sinon défaut de la config.
        Schema::table('cinema_chains', function (Blueprint $table) {
            $table->decimal('revenue_share_percent', 5, 2)->nullable();
        });

        Schema::table('configs', function (Blueprint $table) {
            $table->decimal('default_revenue_share_percent', 5, 2)->default(100);
        });

        // Lien fiable ligne de facture -> chaîne (jusqu'ici seulement le nom).
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->unsignedBigInteger('cinema_chain_id')->nullable()->index();
        });

        DB::statement('UPDATE invoice_lines il JOIN cinema_chains cc ON cc.name = il.cinema_chain SET il.cinema_chain_id = cc.id WHERE il.cinema_chain_id IS NULL');
    }

    public function down(): void
    {
        Schema::table('invoice_lines', fn (Blueprint $t) => $t->dropColumn('cinema_chain_id'));
        Schema::table('configs', fn (Blueprint $t) => $t->dropColumn('default_revenue_share_percent'));
        Schema::table('cinema_chains', fn (Blueprint $t) => $t->dropColumn('revenue_share_percent'));
    }
};
