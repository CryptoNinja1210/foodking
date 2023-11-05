<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray( $request ) : array
    {
        return [
            "id"          => $this->id,
            "name"        => $this->name,
            "guard"       => $this->guard_name,
            'users_count' => $this->users_count
        ];

    }

}
