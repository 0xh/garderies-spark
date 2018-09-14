<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Booking extends JsonResource
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

        $data['start_date']     = $this->start->format('d.m.Y');
        $data['end_date']       = $this->end->format('d.m.Y');
        $data['start_hour']     = $this->start->format('H:i');
        $data['end_hour']       = $this->end->format('H:i');

        if ($this->request) {
            $data['workgroup'] = $this->request->workgroup->name;
        }

        return $data;
    }
}
