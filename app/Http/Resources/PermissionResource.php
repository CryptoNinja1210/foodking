<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'name'   => $this->name,
            'url'    => $this->url,
            'access' => $this->access ?? false
        ];
    }

}
