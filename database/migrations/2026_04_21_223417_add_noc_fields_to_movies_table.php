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
            $table->unsignedBigInteger('moviescods_id')->nullable()->after('id');
            $table->string('code')->nullable()->after('moviescods_id');
            $table->string('title')->nullable()->after('code');
            $table->string('titleShort')->nullable()->after('title');
            $table->string('spl_uuid')->nullable()->after('titleShort');
            $table->boolean('exist_inPos')->default(false)->after('spl_uuid');
            $table->date('date_linking')->nullable()->after('exist_inPos');
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['moviescods_id', 'code', 'title', 'titleShort', 'spl_uuid', 'exist_inPos', 'date_linking']);
        });
    }
};
