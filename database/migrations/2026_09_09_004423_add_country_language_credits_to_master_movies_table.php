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
        Schema::table('master_movies', function (Blueprint $table) {
            $table->string('country')->nullable()->after('plot');
            $table->string('language')->nullable()->after('country');
            $table->string('director')->nullable()->after('language');
            $table->text('actors')->nullable()->after('director');
            $table->string('writer')->nullable()->after('actors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_movies', function (Blueprint $table) {
            $table->dropColumn(['country', 'language', 'director', 'actors', 'writer']);
        });
    }
};
