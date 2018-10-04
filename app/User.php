<?php

namespace App;

use Laravel\Spark\CanJoinTeams;
use Laravel\Spark\User as SparkUser;

class User extends SparkUser
{
    use CanJoinTeams;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'two_factor_reset_code',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at'         => 'datetime',
        'uses_two_factor_auth'  => 'boolean',
        'contact_preferences'   => 'array',
    ];

    protected $dates = [
        'birthdate'
    ];

    /**
     * Override the tax percentage for Switzerland, because the rest of the world doesn't know about us.
     *
     * @return float|int
     */
    public function taxPercentage()
    {
        if ($this->card_country == 'CH') {
            return 7.7;
        }
    }

    public function routeNotificationForNexmo($notification)
    {
        return $this->phone;
    }

    public function nursery()
    {
        return $this->belongsTo('App\Nursery');
    }

    public function availabilities()
    {
        return $this->hasMany('App\Availability');
    }

    public function bookings()
    {
        return $this->hasMany('App\Booking', 'substitute_id', 'id');
    }

    public function ownBookings()
    {
        return $this->hasMany('App\Booking', 'user_id', 'id');
    }

    public function bookingRequests()
    {
        return $this->hasMany('App\BookingRequest', 'substitute_id', 'id');
    }

    public function ownBookingRequests()
    {
        return $this->hasMany('App\BookingRequest', 'user_id', 'id');
    }

    public function managedNetworks()
    {
        return $this->hasMany('App\Network');
    }

    public function networks()
    {
        return $this->belongsToMany('App\Network');
    }

    public function diploma()
    {
        return $this->belongsTo('App\Diploma');
    }

    public function workgroups()
    {
        return $this->belongsToMany('App\Workgroup');
    }

    public function favorite_substitutes()
    {
        return $this->belongsToMany('App\User', 'user_favorites','user_id', 'substitute_id');
    }

    public function isSuperAdmin()
    {
        $devs = \Spark::$developers;
        if (in_array($this->email, $devs)) {
            return true;
        }
        return false;
    }

}
