<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMovie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'year',
        'rating',
        'runtime',
        'plot',
        'image',
    ];

    public function genres()
    {
        return $this->belongsToMany(MovieGenre::class, 'master_movie_movie_genre');
    }

    public function movies()
    {
        return $this->hasMany(Movie::class, 'master_movie_id');
    }
}
