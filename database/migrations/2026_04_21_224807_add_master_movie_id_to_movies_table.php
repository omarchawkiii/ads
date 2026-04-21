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
        Schema::table('movies', function (Blueprint $table) {
            $table->unsignedBigInteger('master_movie_id')->nullable()->after('id');
            $table->foreign('master_movie_id')->references('id')->on('master_movies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropForeign(['master_movie_id']);
            $table->dropColumn('master_movie_id');
        });
    }
};
