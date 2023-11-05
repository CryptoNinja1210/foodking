<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryBoyOrderCountResource extends JsonResource
{
    public $info;

    public function __construct($info)
    {
        parent::__construct($info);
        $this->info = $info;
    }

    public function toArray($request)
    {
        return [
            "total_delivered" => $this->info['total_delivered'],
            "total_returned"  => $this->info['total_returned']
        ];
    }
}
