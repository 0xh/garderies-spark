<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Network extends JsonResource
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

        $data['link']               = route('networks.show', $this->id);
        $data['owner']['name']      = $this->owner->name ?? '-';
        $data['owner']['link']      = route('users.show', $this->owner->id);
        $data['employees']          = $this->users()->count();

        return $data;
    }
}
