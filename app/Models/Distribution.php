<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $fillable = [
        'claim_id',
        'receiver_name',
        'receiver_type',
        'location',
        'story',
        'photo_path'
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }
}