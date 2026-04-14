<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'donation_id',
        'volunteer_id',
        'claimed_at',
        'status'
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function distribution()
    {
        return $this->hasOne(Distribution::class);
    }
}