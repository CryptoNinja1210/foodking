<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description === null ? '' : $this->description,
            'status'      => $this->status,
            'image'       => $this->image
        ];
    }
}
