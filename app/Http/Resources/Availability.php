<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class Availability extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $start_day  = $this->start->format('d.m.Y');
        $end_day    = $this->end->format('d.m.Y');
        $start_hour = $this->start->format('H\hi');
        $end_hour   = $this->end->format('H\hi');

        $data['matching']           = $this->matching;
        $data['start']              = $start_day;
        $data['end']                = $end_day;
        $data['start_hour']         = $start_hour;
        $data['end_hour']           = $end_hour;

        if (!$this->user) { return $data; }

        $data['user']               = $this->user;
        $data['user']['link']       = route('users.show', $this->user ?? 0);
        $data['nursery']            = $this->user->nursery;
        $data['nursery']['link']    = route('nurseries.show', $this->user->nursery ?? 0);
        $data['networks']           = $this->user->networks;

        $authUser = User::find($request->uid);
        $data['favorite'] = false;
        if ( $authUser->favorite_substitutes->where('id', $this->user->id)->count() ) {
            $data['favorite'] = true;
        }

        return $data;
    }
}
