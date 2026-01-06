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
        Schema::create('compaign_cinema_chain', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compaign_id')
                    ->constrained('compaigns')
                    ->cascadeOnDelete();

            $table->foreignId('cinema_chain_id')
                    ->constrained('cinema_chains')
                    ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['compaign_id', 'cinema_chain_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compaign_cinema_chain');
    }
};
