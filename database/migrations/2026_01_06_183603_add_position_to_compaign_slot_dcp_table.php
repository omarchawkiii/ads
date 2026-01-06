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
        Schema::table('compaign_slot_dcp', function (Blueprint $table) {
            $table
            ->unsignedInteger('position')
            ->after('slot_id')
            ->comment('Order of DCP inside the slot');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compaign_slot_dcp', function (Blueprint $table) {
            $table->dropUnique('uniq_compaign_slot_position');
            $table->dropColumn('position');
        });
    }
};
