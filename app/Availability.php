<?php

namespace App;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Availability extends Model
{
    use SoftDeletes;

    public const STATUS_UNTOUCHED           = 0;
    public const STATUS_BOOKED              = 1;
    public const STATUS_PARTIALLY_BOOKED    = 2;
    public const STATUS_ARCHIVED            = 3;

    public const STATUS_UNTOUCHED_LABEL         = 'Libre';
    public const STATUS_BOOKED_LABEL            = 'Réservé';
    public const STATUS_PARTIALLY_BOOKED_LABEL  = 'Part. réservé';
    public const STATUS_ARCHIVED_LABEL          = 'Archivé';

    protected $dates = ['start', 'end', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function requests()
    {
        return $this->hasMany('App\BookingRequest');
    }

    public function networks()
    {
        return $this->hasManyThrough('App\Network', 'App\User', 'id', 'id');
    }

    public function bookings()
    {
        return $this->hasManyThrough(
            'App\Booking',
            'App\BookingRequest',
            'availability_id',
            'request_id');
    }
}
