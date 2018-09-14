<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Nursery extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'bookings_count'    => $this->bookings->count(),
            'employees_count'   => $this->users->count(),
            'network'           => optional($this->network),
            'link'              => route('nurseries.show', [$this->id])
        ];

    }
}
