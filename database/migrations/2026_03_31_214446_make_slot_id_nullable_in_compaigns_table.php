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
            $table->dropForeign(['slot_id']);
            $table->unsignedBigInteger('slot_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('slot_id')->nullable(false)->change();
            $table->foreign('slot_id')->references('id')->on('slots');
        });
    }
};
