<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CinemaChain extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',

    ];

    public function compaigns()
    {
        return $this->belongsToMany(
            Compaign::class,
            'compaign_cinema_chain'
        )->withTimestamps();
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
