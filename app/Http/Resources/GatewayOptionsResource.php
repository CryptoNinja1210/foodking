<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class GatewayOptionsResource extends JsonResource
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
            'id' => $this->id,
            'option' => $this->option,
            'value' => $this->value,
            'type' => $this->type,
            'activities' => json_decode($this->activities)
        ];
    }

}
