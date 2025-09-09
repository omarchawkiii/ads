<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DcpCreative extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
        'duration',
        'path',
    ];
    public function compaigns()
    {
        return $this->belongsToMany(Compaign::class, 'compaign_dcp_creative');
    }


}
