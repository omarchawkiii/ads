<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid',
        'movie_genre_id',
        'langue_id',
        'runtime',
        'status',
        //'play_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
       // 'play_at' => 'datetime',
    ];

    public function compaigns(): HasMany
    {
        return $this->hasMany(Compaign::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(MovieGenre::class, 'movie_genre_id');
    }
    public function langue()
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }
}
