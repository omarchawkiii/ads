<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // % de partage de la chaîne figé sur chaque ligne au moment de la facturation.
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->decimal('share_percent', 5, 2)->nullable();
        });

        // Lignes existantes : on fige le % en vigueur aujourd'hui (propre à la chaîne, sinon défaut de la config, sinon 100).
        DB::statement(
            'UPDATE invoice_lines il
             LEFT JOIN cinema_chains cc ON cc.id = il.cinema_chain_id
             SET il.share_percent = COALESCE(cc.revenue_share_percent, (SELECT default_revenue_share_percent FROM configs LIMIT 1), 100)
             WHERE il.share_percent IS NULL AND il.cinema_chain_id IS NOT NULL'
        );
    }

    public function down(): void
    {
        Schema::table('invoice_lines', fn (Blueprint $t) => $t->dropColumn('share_percent'));
    }
};
