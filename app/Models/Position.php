<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_id',
        'type',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

}
