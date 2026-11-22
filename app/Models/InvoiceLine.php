<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $fillable = [
        'invoice_id', 'dcp_name', 'slot_name', 'cinema_chain', 'cinema_chain_id', 'share_percent', 'location_name',
        'played', 'location_price', 'slot_price', 'hall_price', 'hall_types', 'amount',
        'line_type', 'unit_price', 'multiplier', 'multiplier_raw', 'multiplier_position', 'multiplier_targeting',
        'multiplier_length', 'multiplier_season', 'multiplier_volume', 'targeting_level',
    ];
}
