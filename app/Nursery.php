<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nursery extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $table = 'nurseries';
    protected $dates = ['deleted_at'];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'name']];
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function team()
    {
        return $this->belongsTo('App\Team');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function bookings()
    {
        return $this->hasMany('App\Booking');
    }

    public function bookingRequests()
    {
        return $this->hasMany('App\BookingRequests');
    }

    public function network()
    {
        return $this->belongsTo('App\Network');
    }

    public function ads()
    {
        return $this->hasMany('App\Ad');
    }

    public function schedule()
    {
        return $this->hasManyThrough('App\Schedule', 'App\User');
    }
}
