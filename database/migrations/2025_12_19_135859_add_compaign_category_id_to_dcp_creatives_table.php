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
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->integer('status')->default(1); // 1 Pending Approval 2-Approve 3-Reject
            $table->unsignedBigInteger('compaign_category_id')
                  ->nullable()
                  ->after('id');

            $table->foreign('compaign_category_id')
                  ->references('id')
                  ->on('compaign_categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->dropForeign(['compaign_category_id']);
            $table->dropColumn('compaign_category_id', 'status');
        });
    }
};
