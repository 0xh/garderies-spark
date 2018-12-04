<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public const TYPE_WORKING            = 0;
    public const TYPE_BREAK              = 1;
    public const TYPE_WORKING_NO_KIDS    = 2;
    public const TYPE_MEETING            = 3;

    protected $dates = ['start', 'end', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
