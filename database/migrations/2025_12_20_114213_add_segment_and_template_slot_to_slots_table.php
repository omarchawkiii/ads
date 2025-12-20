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
        Schema::table('slots', function (Blueprint $table) {
            $table->string('segment_name')->nullable()->after('name');
            $table->unsignedBigInteger('template_slot_id')->nullable()->after('segment_name');

            $table->foreign('template_slot_id')
                  ->references('id')
                  ->on('template_slots')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            $table->dropForeign(['template_slot_id']);
            $table->dropColumn(['segment_name', 'template_slot_id']);
        });
    }
};
