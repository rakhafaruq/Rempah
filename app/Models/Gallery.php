<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'distribution_id',
        'photo_path'
    ];

    public function distribution()
    {
        return $this->belongsTo(Distribution::class);
    }
}