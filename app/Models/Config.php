<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
       'use_noc',
       'link',
       'user',
       'password',
       'tax',
       'default_location_price',
       'default_slot_price',
       'default_hall_type_price',
       'default_revenue_share_percent',
       'position_first_multiplier',
       'position_middle_multiplier',
       'position_premium_multiplier',
       'target_region_multiplier',
       'target_movie_multiplier',
       'target_audience_multiplier',
       'length_15_multiplier',
       'length_30_multiplier',
       'length_45_multiplier',
       'length_60_multiplier',
       'volume_min_weeks',
       'volume_multiplier',
       'max_multiplier',
       'min_outlet_week_price',
    ];

    /** Champs de réglage des multiplicateurs (tarification « deck »), avec leur valeur par défaut. */
    public const PRICING_DEFAULTS = [
        'position_first_multiplier'   => 1.00,
        'position_middle_multiplier'  => 1.15,
        'position_premium_multiplier' => 1.30,
        'target_region_multiplier'    => 1.20,
        'target_movie_multiplier'     => 1.30,
        'target_audience_multiplier'  => 1.40,
        'length_15_multiplier'        => 0.60,
        'length_30_multiplier'        => 1.00,
        'length_45_multiplier'        => 1.40,
        'length_60_multiplier'        => 1.80,
        'volume_min_weeks'            => 4,
        'volume_multiplier'           => 0.90,
        'max_multiplier'              => 2.50,
        'min_outlet_week_price'       => 1000.00,
    ];

}
