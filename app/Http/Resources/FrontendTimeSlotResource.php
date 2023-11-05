<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class FrontendTimeSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            "label"     => $this->label,
            "from_time" => $this->from_time,
            "to_time"   => $this->to_time,
            "time"      => $this->time

        ];
    }
}
