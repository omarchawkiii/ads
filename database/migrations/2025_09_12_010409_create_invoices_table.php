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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->integer('number');
            $table->date('date');
            $table->date('due_date');
            $table->integer('status');
            $table->integer('discount');
            $table->integer('tax');
            $table->decimal('total_ttc', 8, 2)->nullable();
            $table->decimal('total_ht', 8, 2)->nullable();
            $table->decimal('total_tax', 8, 2)->nullable();

            $table->foreignId('compaign_id')
                  ->nullable()
                  ->constrained('compaigns')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
