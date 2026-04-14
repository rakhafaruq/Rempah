<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'title',
        'description',
        'donor_id',
        'location',
        'pickup_deadline',
        'total_portion',
        'remaining_portion',
        'status',
        'photo_path'
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
    public function isExpired()
    {
        return now()->greaterThan($this->pickup_deadline);
    }
}