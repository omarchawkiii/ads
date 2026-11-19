<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompaignPlayout extends Model
{
    protected $fillable = [
        'compaign_id', 'dcp_creative_id', 'cinema_chain_id', 'location_name',
        'date', 'played', 'incomplete', 'failed', 'synced_until',
    ];

    protected $casts = [
        'date'         => 'date:Y-m-d',
        'synced_until' => 'datetime',
    ];
}
