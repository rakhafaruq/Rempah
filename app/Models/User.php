<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name', 'email', 'phone','password','role'
    ];

    protected $hidden = [
        'password'
    ];

    public function donor()
    {
        return $this->hasOne(Donor::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'volunteer_id');
    }
}