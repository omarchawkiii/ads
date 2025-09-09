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
        Schema::create('compaign_dcp_creative', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compaign_id')->constrained('compaigns')->cascadeOnDelete();
            $table->foreignId('dcp_creative_id')->constrained('dcp_creatives')->cascadeOnDelete();
            $table->unique(['compaign_id','dcp_creative_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compaign_dcp_creative');
    }
};
