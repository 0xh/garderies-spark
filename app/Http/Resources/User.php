<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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

        $data['link']            = route('users.show', [$this->id]);
        $data['nursery']['name'] = $this->nursery->name ?? '-';
        $data['nursery']['link'] = route('nurseries.show', $this->nursery->id ?? 0);
        $data['networks']        = $this->networks()->get();

        return $data;
    }
}
