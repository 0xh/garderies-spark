<?php

namespace App;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingRequest extends Model
{
    use SoftDeletes;

    public const STATUS_PENDING     = 0;
    public const STATUS_APPROVED    = 1;
    public const STATUS_DENIED      = 2;

    protected $dates = ['start', 'end', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function substitute()
    {
        return $this->belongsTo('App\User', 'substitute_id');
    }

    public function availability()
    {
        return $this->belongsTo('App\Availability');
    }

    public function nursery()
    {
        return $this->belongsTo('App\Nursery');
    }

    public function booking()
    {
        return $this->hasOne('App\Booking', 'request_id');
    }

    public function workgroup()
    {
        return $this->belongsTo('App\Workgroup');
    }

}
