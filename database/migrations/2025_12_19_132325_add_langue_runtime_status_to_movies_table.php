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
            $table->unsignedBigInteger('langue_id')->nullable()->after('id');
            $table->integer('runtime')->nullable()->after('langue_id');
            $table->boolean('status')->default(true)->after('runtime');

            $table->foreign('langue_id')
                  ->references('id')
                  ->on('langues')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropForeign(['langue_id']);
            $table->dropColumn(['langue_id', 'runtime', 'status']);
        });
    }
};
