<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $fillable = [
        'invoice_id', 'dcp_name', 'slot_name', 'cinema_chain', 'location_name',
        'played', 'location_price', 'slot_price', 'hall_price', 'hall_types', 'amount',
    ];
}
