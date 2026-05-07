<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->string('extract_path')->nullable()->after('path');
            $table->string('preview_path')->nullable()->after('extract_path');
            $table->enum('preview_status', ['pending', 'processing', 'ready', 'failed'])
                  ->default('pending')
                  ->after('preview_path');
        });
    }

    public function down(): void
    {
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->dropColumn(['extract_path', 'preview_path', 'preview_status']);
        });
    }
};
