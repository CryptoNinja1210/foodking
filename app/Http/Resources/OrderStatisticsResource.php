<?php

namespace App\Http\Resources;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatisticsResource extends JsonResource
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
            "total_order"            => $this->info['total_order'],
            "pending_order"          => $this->info['pending_order'],
            "processing_order"       => $this->info['processing_order'],
            "out_for_delivery_order" => $this->info['out_for_delivery_order'],
            "delivered_order"        => $this->info['delivered_order'],
            "canceled_order"         => $this->info['canceled_order'],
            "returned_order"         => $this->info['returned_order'],
            "rejected_order"         => $this->info['rejected_order'],
        ];
    }
}