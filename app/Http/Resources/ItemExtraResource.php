<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemExtraResource extends JsonResource
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
            'id'             => $this->id,
            'item_id'        => $this->item_id,
            'name'           => $this->name,
            'price'          => $this->price,
            'currency_price' => AppLibrary::currencyAmountFormat($this->price),
            'flat_price'     => AppLibrary::flatAmountFormat($this->price),
            'convert_price'  => AppLibrary::convertAmountFormat($this->price),
            'status'         => $this->status,
            "item"           => optional($this->item)->name,
        ];
    }
}
