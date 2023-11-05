<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ItemCategoryMenuResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description === null ? '' : $this->description,
            'status'      => $this->status,
            'thumb'       => $this->thumb,
            'cover'       => $this->cover,
            'items'       => ItemResource::collection($this->items),
        ];
    }
}
