<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class DcpCreative extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'uuid',
        'name',
        'duration',
        'edit_rate',
        'path',
        'extract_path',
        'preview_path',
        'preview_status',
        'compaign_category_id',
        'compaign_objective_id',
        'langue_id',
        'gender_id',
        'user_id',
        'customer_id',
        'status',
        'approval_note',
    ];
    public function compaigns()
    {
        return $this->belongsToMany(Compaign::class, 'compaign_dcp_creative');
    }


    public function compaignCategory(): BelongsTo
    {
        return $this->belongsTo(CompaignCategory::class, 'compaign_category_id');
    }

    public function compaignObjective(): BelongsTo
    {
        return $this->belongsTo(CompaignObjective::class, 'compaign_objective_id');
    }

    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function targetTypes()
    {
        return $this->belongsToMany(TargetType::class, 'dcp_creative_target_type');
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'dcp_creative_interest');
    }

    public function slots()
    {
        return $this->belongsToMany(
            Slot::class,
            'compaign_slot_dcp',
            'dcp_creative_id',
            'slot_id'
        )->withPivot('compaign_id')
         ->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
