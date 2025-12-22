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
        Schema::create('compaign_slot_dcp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compaign_id');
            $table->unsignedBigInteger('slot_id');
            $table->unsignedBigInteger('dcp_creative_id');

            // optionnels selon ton besoin
            // $table->integer('position')->nullable();
            // $table->integer('start_second')->nullable();


            $table->unique(['compaign_id', 'slot_id', 'dcp_creative_id'], 'csd_unique');

            $table->foreign('compaign_id')
                ->references('id')->on('compaigns')
                ->onDelete('cascade');

            $table->foreign('slot_id')
                ->references('id')->on('slots')
                ->onDelete('cascade');

            $table->foreign('dcp_creative_id')
                ->references('id')->on('dcp_creatives')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compaign_slot_dcp');
    }
};
