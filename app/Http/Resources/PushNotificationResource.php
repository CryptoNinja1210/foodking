<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class PushNotificationResource extends JsonResource
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
            'description' => strip_tags($this->description),
            'image'       => $this->image,
            'role_id'     => $this->role_id,
            'role'        => $this->role_id == 0 ? trans('all.label.all_roles') : optional($this->role)->name,
            'user_id'     => $this->user_id,
            'customer'    => $this->user_id == 0 ? trans('all.label.all_users') : optional($this->customer)->name,
        ];
    }
}
