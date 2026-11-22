<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Période tarifaire (blockbuster, fêtes…) : multiplicateur appliqué aux jours de campagne qui la recoupent. */
class PricingSeason extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'multiplier'];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date'   => 'date:Y-m-d',
        'multiplier' => 'float',
    ];
}
