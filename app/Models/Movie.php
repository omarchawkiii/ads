<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'uuid',
        'movie_genre_id',
        'langue_id',
        'runtime',
        'status',
        'moviescods_id',
        'code',
        'title',
        'titleShort',
        'spl_uuid',
        'exist_inPos',
        'date_linking',
        'master_movie_id',
        'cinema_chain_id',
    ];

    protected $casts = [
        'id'           => 'integer',
        'exist_inPos'  => 'boolean',
        'date_linking' => 'date',
    ];


    public function compaigns(): BelongsToMany
    {
        return $this->belongsToMany(
            Compaign::class,
            'compaign_movie'
        )->withTimestamps();
    }
    public function genre(): BelongsTo
    {
        return $this->belongsTo(MovieGenre::class, 'movie_genre_id');
    }
    public function langue()
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }

    public function masterMovie()
    {
        return $this->belongsTo(MasterMovie::class, 'master_movie_id');
    }

    public function cinemaChain()
    {
        return $this->belongsTo(CinemaChain::class, 'cinema_chain_id');
    }
}
