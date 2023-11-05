<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'id'              => $this->id,
            'title'           => $this->title,
            'slug'            => $this->slug,
            'description'     => $this->description === null ? '' : $this->description,
            'menu_section_id' => $this->menu_section_id === null ? '' : $this->menu_section_id,
            'template_id'     => $this->template_id === null ? '' : $this->template_id,
            'status'          => $this->status,
            'image'           => $this->image
        ];
    }
}
