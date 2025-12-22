<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compaign extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'compaign_objective_id',
        'compaign_category_id',
        'start_date',
        'end_date',
        'budget',
        'langue_id',
        'note',
        'movie_id',
        'gender_id',
        'slot_id',
        'ad_duration',
        'status', // 1-pendign 2-approve  3-drafts  4-rejected
        'user_id',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'budget'      => 'integer',
        'ad_duration' => 'integer',
    ];



    public function compaignObjective()
    {
        return $this->belongsTo(CompaignObjective::class);
    }
    public function compaignCategory()
    {
        return $this->belongsTo(CompaignCategory::class);
    }
    public function langue()
    {
        return $this->belongsTo(Langue::class);
    }
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    // many-to-many
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'compaign_brand');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'compaign_location');
    }

    // (déjà présents)
    public function targetTypes()
    {
        return $this->belongsToMany(TargetType::class, 'compaign_target_type');
    }
    public function hallTypes()
    {
        return $this->belongsToMany(HallType::class, 'compaign_hall_type');
    }
    public function movieGenres()
    {
        return $this->belongsToMany(MovieGenre::class, 'compaign_movie_genre');
    }
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'compaign_interest');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }


    public function templateSlot()
    {
        return $this->belongsTo(TemplateSlot::class);
    }
    public function slots()
    {
        return $this->belongsToMany(Slot::class, 'compaign_slot')
                    ->withTimestamps();
    }

    public function dcpCreatives()
    {
        return $this->belongsToMany(
            DcpCreative::class,
            'compaign_slot_dcp',
            'compaign_id',
            'dcp_creative_id'
        )->withPivot('slot_id')
        ->withTimestamps();
    }




}
