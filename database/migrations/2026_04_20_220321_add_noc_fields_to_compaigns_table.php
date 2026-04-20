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
        Schema::table('compaigns', function (Blueprint $table) {
            $table->boolean('noc_sent')->default(false)->after('status');
            $table->text('noc_note')->nullable()->after('noc_sent');
        });
    }

    public function down(): void
    {
        Schema::table('compaigns', function (Blueprint $table) {
            $table->dropColumn(['noc_sent', 'noc_note']);
        });
    }
};
