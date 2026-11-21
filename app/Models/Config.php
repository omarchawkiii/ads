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
    ];

}
