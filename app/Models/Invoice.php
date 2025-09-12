<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'compaign_id',
        'date',
        'due_date',
        'status',
        'discount',
        'tax',
        'total_ttc',
        'total_ht',
    ];

    public function compaign()
    {
        return $this->belongsTo(Compaign::class);
    }
}
