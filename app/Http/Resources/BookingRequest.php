<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingRequest extends JsonResource
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
        $data['url'] = route('booking-requests.show', $this->id);
        $data['start_date_formatted'] = $this->start->format('d.m.Y');
        $data['end_date_formatted'] = $this->end->format('d.m.Y');
        $data['start_hour_formatted'] = $this->start->format('H:i');
        $data['end_hour_formatted'] = $this->end->format('H:i');

        return $data;
    }
}
