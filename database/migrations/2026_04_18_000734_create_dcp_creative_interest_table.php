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
        Schema::create('dcp_creative_interest', function (Blueprint $table) {
            $table->unsignedBigInteger('dcp_creative_id');
            $table->unsignedBigInteger('interest_id');
            $table->primary(['dcp_creative_id', 'interest_id']);
            $table->foreign('dcp_creative_id')->references('id')->on('dcp_creatives')->cascadeOnDelete();
            $table->foreign('interest_id')->references('id')->on('interests')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dcp_creative_interest');
    }
};
