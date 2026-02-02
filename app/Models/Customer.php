<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'user_id',
    ];

    /** Relations */

    // Customer → Users (one to many)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Customer → DcpCreative (one to many)
    public function dcpCreatives()
    {
        return $this->hasMany(DcpCreative::class);
    }

    // Customer → Owner
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
