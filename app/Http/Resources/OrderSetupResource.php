<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class OrderSetupResource extends JsonResource
{

    public $info;

    public function __construct($info)
    {
        parent::__construct($info);
        $this->info = $info;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "order_setup_food_preparation_time"        => $this->info['order_setup_food_preparation_time'],
            "order_setup_schedule_order_slot_duration" => $this->info['order_setup_schedule_order_slot_duration'],
            "order_setup_takeaway"                     => $this->info['order_setup_takeaway'],
            "order_setup_delivery"                     => $this->info['order_setup_delivery'],
            "order_setup_free_delivery_kilometer"      => $this->info['order_setup_free_delivery_kilometer'],
            "order_setup_basic_delivery_charge"        => $this->info['order_setup_basic_delivery_charge'],
            "order_setup_charge_per_kilo"              => $this->info['order_setup_charge_per_kilo'],
        ];
    }
}