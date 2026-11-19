<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Diffusions confirmées par jour (source : NOC ads_played → by_location[].by_day[]).
        Schema::create('compaign_playouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compaign_id')->constrained('compaigns')->cascadeOnDelete();
            $table->foreignId('dcp_creative_id')->constrained('dcp_creatives')->cascadeOnDelete();
            $table->foreignId('cinema_chain_id')->constrained('cinema_chains')->cascadeOnDelete();
            $table->string('location_name', 150);
            $table->date('date');
            $table->unsignedInteger('played')->default(0);
            $table->unsignedInteger('incomplete')->default(0);
            $table->unsignedInteger('failed')->default(0);
            $table->dateTime('synced_until')->nullable();
            $table->timestamps();

            $table->unique(
                ['compaign_id', 'dcp_creative_id', 'cinema_chain_id', 'location_name', 'date'],
                'compaign_playouts_unique'
            );
        });

        // Totaux par pub / chaîne / location. Le NOC ne donne played_seconds que sur ce niveau
        // (pas par jour). location_name = '' : ligne vide (aucune diffusion) qui garde synced_until.
        Schema::create('compaign_playout_totals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compaign_id')->constrained('compaigns')->cascadeOnDelete();
            $table->foreignId('dcp_creative_id')->constrained('dcp_creatives')->cascadeOnDelete();
            $table->foreignId('cinema_chain_id')->constrained('cinema_chains')->cascadeOnDelete();
            $table->string('location_name', 150)->default('');
            $table->unsignedInteger('played')->default(0);
            $table->unsignedInteger('incomplete')->default(0);
            $table->unsignedInteger('failed')->default(0);
            $table->unsignedBigInteger('played_seconds')->default(0);
            $table->dateTime('synced_until')->nullable();
            $table->dateTime('fetched_at');
            $table->timestamps();

            $table->unique(
                ['compaign_id', 'dcp_creative_id', 'cinema_chain_id', 'location_name'],
                'compaign_playout_totals_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compaign_playout_totals');
        Schema::dropIfExists('compaign_playouts');
    }
};
