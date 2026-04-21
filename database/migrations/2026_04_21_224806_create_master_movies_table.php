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
        Schema::create('master_movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('year')->nullable();
            $table->string('rating')->nullable();
            $table->integer('runtime')->nullable();
            $table->text('plot')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('master_movie_movie_genre', function (Blueprint $table) {
            $table->unsignedBigInteger('master_movie_id');
            $table->unsignedBigInteger('movie_genre_id');
            $table->primary(['master_movie_id', 'movie_genre_id']);
            $table->foreign('master_movie_id')->references('id')->on('master_movies')->cascadeOnDelete();
            $table->foreign('movie_genre_id')->references('id')->on('movie_genres')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_movies');
    }
};
