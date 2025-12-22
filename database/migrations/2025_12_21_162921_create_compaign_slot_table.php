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
        Schema::create('compaign_slot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compaign_id');
            $table->unsignedBigInteger('slot_id');

            $table->unique(['compaign_id', 'slot_id']);

            $table->foreign('compaign_id')
                ->references('id')->on('compaigns')
                ->onDelete('cascade');

            $table->foreign('slot_id')
                ->references('id')->on('slots')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compaign_slot');
    }
};
