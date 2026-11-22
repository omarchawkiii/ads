<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Modèle tarifaire du deck « AdSmart Network » : prix par diffusion × multiplicateurs.
 * Les prix additifs existants (location + slot + types de salle) restent le prix de base.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            // Position dans la séance (portée par le slot).
            $table->decimal('position_first_multiplier', 5, 2)->default(1.00);
            $table->decimal('position_middle_multiplier', 5, 2)->default(1.15);
            $table->decimal('position_premium_multiplier', 5, 2)->default(1.30);
            // Ciblage (tous les sites = 1.0 ; le plus haut niveau applicable est retenu).
            $table->decimal('target_region_multiplier', 5, 2)->default(1.20);
            $table->decimal('target_movie_multiplier', 5, 2)->default(1.30);
            $table->decimal('target_audience_multiplier', 5, 2)->default(1.40);
            // Durée de la pub (tranches : <= 15 s, <= 30 s, <= 45 s, au-delà).
            $table->decimal('length_15_multiplier', 5, 2)->default(0.60);
            $table->decimal('length_30_multiplier', 5, 2)->default(1.00);
            $table->decimal('length_45_multiplier', 5, 2)->default(1.40);
            $table->decimal('length_60_multiplier', 5, 2)->default(1.80);
            // Remise volume : campagne d'au moins N semaines.
            $table->unsignedSmallInteger('volume_min_weeks')->default(4);
            $table->decimal('volume_multiplier', 5, 2)->default(0.90);
            // Plafond du multiplicateur cumulé et minimum par cinéma et par semaine.
            $table->decimal('max_multiplier', 5, 2)->default(2.50);
            $table->decimal('min_outlet_week_price', 8, 2)->default(1000.00);
        });

        Schema::table('slots', function (Blueprint $table) {
            $table->string('position_tier', 10)->nullable()->after('price'); // first | middle | premium
        });

        Schema::create('pricing_seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('multiplier', 5, 2)->default(1.20);
            $table->timestamps();
        });

        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->string('line_type', 10)->default('plays'); // plays | minimum
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('multiplier', 6, 3)->default(1);
            $table->decimal('multiplier_raw', 6, 3)->default(1);
            $table->decimal('multiplier_position', 6, 3)->default(1);
            $table->decimal('multiplier_targeting', 6, 3)->default(1);
            $table->decimal('multiplier_length', 6, 3)->default(1);
            $table->decimal('multiplier_season', 6, 3)->default(1);
            $table->decimal('multiplier_volume', 6, 3)->default(1);
            $table->string('targeting_level', 20)->nullable();
        });

        // Factures déjà générées : prix unitaire = ancien prix additif, multiplicateurs = 1.
        DB::statement('UPDATE invoice_lines SET unit_price = location_price + slot_price + hall_price WHERE unit_price IS NULL');

        // Alignement sur le deck (taxe SST 8 %, partage 60 % cinéma) : uniquement si la config
        // porte encore les anciennes valeurs par défaut (6 % / 100 %). Un réglage choisi est conservé.
        DB::table('configs')->where('tax', 6)->update(['tax' => 8]);
        DB::table('configs')->where('default_revenue_share_percent', 100)->update(['default_revenue_share_percent' => 60]);
        // Prix de base d'environ RM 7 par diffusion si aucun prix par défaut n'a été saisi.
        DB::table('configs')
            ->where('default_location_price', 0)->where('default_slot_price', 0)->where('default_hall_type_price', 0)
            ->update(['default_location_price' => 5, 'default_slot_price' => 2]);
    }

    public function down(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->dropColumn([
                'line_type', 'unit_price', 'multiplier', 'multiplier_raw', 'multiplier_position',
                'multiplier_targeting', 'multiplier_length', 'multiplier_season', 'multiplier_volume', 'targeting_level',
            ]);
        });
        Schema::dropIfExists('pricing_seasons');
        Schema::table('slots', fn (Blueprint $t) => $t->dropColumn('position_tier'));
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn([
                'position_first_multiplier', 'position_middle_multiplier', 'position_premium_multiplier',
                'target_region_multiplier', 'target_movie_multiplier', 'target_audience_multiplier',
                'length_15_multiplier', 'length_30_multiplier', 'length_45_multiplier', 'length_60_multiplier',
                'volume_min_weeks', 'volume_multiplier', 'max_multiplier', 'min_outlet_week_price',
            ]);
        });
    }
};
