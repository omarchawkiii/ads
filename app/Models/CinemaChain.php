<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CinemaChain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'contact_email',
        'ip_address',
        'username',
        'password',
    ];

    public function compaigns()
    {
        return $this->belongsToMany(
            Compaign::class,
            'compaign_cinema_chain'
        )->withTimestamps();
    }

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cinema_chain_user')->withTimestamps();
    }
}
