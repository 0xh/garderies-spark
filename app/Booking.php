<?php

namespace App;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    public const STATUS_PENDING     = 0;
    public const STATUS_APPROVED    = 1;
    public const STATUS_DENIED      = 2;
    public const STATUS_ARCHIVED    = 3;

    public const STATUS_PENDING_LABEL     = 'En attente';
    public const STATUS_APPROVED_LABEL    = 'Approuvé';
    public const STATUS_DENIED_LABEL      = 'Refusé';
    public const STATUS_ARCHIVED_LABEL    = 'Archivé';

    protected $dates = ['start', 'end', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function substitute()
    {
        return $this->belongsTo('App\User', 'substitute_id', 'id');
    }

    public function nursery()
    {
        return $this->belongsTo('App\Nursery');
    }

    public function request()
    {
        return $this->belongsTo('App\BookingRequest', 'request_id');
    }

    public function purpose()
    {
        return $this->belongsTo('App\Purpose');
    }

    public function feedbacks()
    {
        return $this->hasMany('App\Feedback');
    }

}
