<?php

namespace App;

use Laravel\Spark\Team as SparkTeam;

class Team extends SparkTeam
{
    public function networks()
    {
        return $this->hasMany('App\Network');
    }

    public function nurseries()
    {
        return $this->hasMany('App\Nursery');
    }

    public function bookings()
    {
        return $this->hasManyThrough('App\Booking', 'App\Nursery');
    }

    public function bookingRequests()
    {
        return $this->hasManyThrough('App\BookingRequest', 'App\Nursery');
    }
}
