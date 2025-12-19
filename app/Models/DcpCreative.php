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
        'path',
        'compaign_category_id',
        'status',
    ];
    public function compaigns()
    {
        return $this->belongsToMany(Compaign::class, 'compaign_dcp_creative');
    }
    public function compaignCategory(): BelongsTo
    {
        return $this->belongsTo(CompaignCategory::class, 'compaign_category_id');
    }


}
