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
        Schema::create('compaigns', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->foreignId('compaign_objective_id')
                  ->constrained('compaign_objectives')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('compaign_category_id')
                  ->constrained('compaign_categories')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('brand_id')
                  ->constrained('brands')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->integer('budget')->nullable();

            $table->foreignId('langue_id')
                  ->constrained('langues')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->text('note')->nullable();

            $table->foreignId('movie_id')
                  ->constrained('movies')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('gender_id')
                  ->constrained('genders')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('slot_id')
                  ->constrained('slots')
                  ->nullable()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->integer('ad_duration'); // 15/30/45/60, etc.

            $table->foreignId('location_id')
                  ->constrained('locations')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compaign');
    }
};
