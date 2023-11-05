<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ItemVariationGroupByAttributeResource extends JsonResource
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
            'item_attribute_id' => $this->item_attribute_id,
            'name'              => optional($this->item_attribute)->name,
            'children'          => ItemVariationResource::collection($this->children)
        ];
    }

}
