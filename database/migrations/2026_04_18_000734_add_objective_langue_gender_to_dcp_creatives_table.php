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
            $table->unsignedBigInteger('compaign_objective_id')->nullable()->after('compaign_category_id');
            $table->unsignedBigInteger('langue_id')->nullable()->after('compaign_objective_id');
            $table->unsignedBigInteger('gender_id')->nullable()->after('langue_id');

            $table->foreign('compaign_objective_id')->references('id')->on('compaign_objectives')->nullOnDelete();
            $table->foreign('langue_id')->references('id')->on('langues')->nullOnDelete();
            $table->foreign('gender_id')->references('id')->on('genders')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->dropForeign(['compaign_objective_id']);
            $table->dropForeign(['langue_id']);
            $table->dropForeign(['gender_id']);
            $table->dropColumn(['compaign_objective_id', 'langue_id', 'gender_id']);
        });
    }
};
