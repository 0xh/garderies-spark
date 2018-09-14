<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Network extends Model
{
    use SoftDeletes;
    use Sluggable;

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
        return $this->belongsToMany('App\User');
    }

    public function nurseries()
    {
        return $this->hasMany('App\Nursery');
    }

    public function ads()
    {
        return $this->hasManyThrough('App\Ad', 'App\Nursery');
    }
}
