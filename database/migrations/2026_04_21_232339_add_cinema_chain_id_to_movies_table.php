<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->unsignedBigInteger('cinema_chain_id')->nullable()->after('master_movie_id');
            $table->foreign('cinema_chain_id')->references('id')->on('cinema_chains')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropForeign(['cinema_chain_id']);
            $table->dropColumn('cinema_chain_id');
        });
    }
};
