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
        Schema::table('compaigns', function (Blueprint $table) {

            $table->unsignedBigInteger('cinema_chain_id')
                  ->nullable()
                  ->after('compaign_category_id');

            $table->foreign('cinema_chain_id')
                  ->references('id')
                  ->on('cinema_chains')
                  ->nullOnDelete();

            // template_slot_id
            $table->unsignedBigInteger('template_slot_id')
                  ->nullable()
                  ->after('cinema_chain_id');

            $table->foreign('template_slot_id')
                  ->references('id')
                  ->on('template_slots')
                  ->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compaigns', function (Blueprint $table) {
            //
        });
    }
};
