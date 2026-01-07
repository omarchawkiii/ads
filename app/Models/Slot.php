<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cpm',
        'max_duration',
        'segment_name',
        'template_slot_id',
        'max_ad_slot',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /*public function compaigns(): HasMany
    {
        return $this->hasMany(Compaign::class);
    }*/

    public function compaigns()
    {
        return $this->belongsToMany(Compaign::class, 'compaign_slot')
                    ->withTimestamps();
    }

    public function dcps()
    {
        return $this->belongsToMany(
            DcpCreative::class,
            'compaign_slot_dcp',
            'slot_id',
            'dcp_creative_id'
        )->withPivot('compaign_id')
        ->withTimestamps();
    }


    public function templateSlot(): BelongsTo
    {
        return $this->belongsTo(TemplateSlot::class);
    }


    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}
