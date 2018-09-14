<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workgroup extends Model
{
    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function bookingRequests()
    {
        return $this->hasMany('App\BookingRequest');
    }
}
