<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompaignPlayoutTotal extends Model
{
    protected $fillable = [
        'compaign_id', 'dcp_creative_id', 'cinema_chain_id', 'location_name',
        'played', 'incomplete', 'failed', 'played_seconds', 'synced_until', 'fetched_at',
    ];

    protected $casts = [
        'synced_until' => 'datetime',
        'fetched_at'   => 'datetime',
    ];
}
