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
        Schema::disableForeignKeyConstraints();

        Schema::create('compaign_target_type_hall_type_movie_genre_interest', function (Blueprint $table) {
            $table->foreignId('compaign_id');
            $table->foreignId('target_type_hall_type_movie_genre_interest_id');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compaign_target_type_hall_type_movie_genre_interest');
    }
};
