<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compaign_master_movie', function (Blueprint $table) {
            $table->unsignedBigInteger('compaign_id');
            $table->unsignedBigInteger('master_movie_id');
            $table->timestamps();

            $table->primary(['compaign_id', 'master_movie_id']);
            $table->foreign('compaign_id')->references('id')->on('compaigns')->cascadeOnDelete();
            $table->foreign('master_movie_id')->references('id')->on('master_movies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compaign_master_movie');
    }
};
