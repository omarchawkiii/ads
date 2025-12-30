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
            if (Schema::hasColumn('compaigns', 'movie_id')) {
                $table->dropForeign(['movie_id']);
                $table->dropColumn('movie_id');
            }
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
