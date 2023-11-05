<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class TimeSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id"           => $this->id,
            "opening_time" => $this->opening_time === null ? '' : $this->opening_time,
            "closing_time" => $this->closing_time === null ? '' : $this->closing_time,
            "day"          => $this->day === null ? '' : $this->day,

        ];
    }
}
