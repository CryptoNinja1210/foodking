<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
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
            "id"            => $this->id,
            "email"         => $this->email,
            'create_date'   => AppLibrary::date($this->created_at),
            'update_date'   => AppLibrary::date($this->updated_at)
        ];
    }
}
