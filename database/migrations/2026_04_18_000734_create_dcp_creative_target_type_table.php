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
        Schema::create('dcp_creative_target_type', function (Blueprint $table) {
            $table->unsignedBigInteger('dcp_creative_id');
            $table->unsignedBigInteger('target_type_id');
            $table->primary(['dcp_creative_id', 'target_type_id']);
            $table->foreign('dcp_creative_id')->references('id')->on('dcp_creatives')->cascadeOnDelete();
            $table->foreign('target_type_id')->references('id')->on('target_types')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dcp_creative_target_type');
    }
};
