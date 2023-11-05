<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferItemResource extends JsonResource
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
            'id'                        => $this->id,
            'offer_id'                  => $this->offer_id,
            'offer_item_id'             => $this->item_id,
            'offer_name'                => optional($this->offer)->name,
            'offer_item_name'           => optional($this->item)->name,
            'offer_item_price'          => optional($this->item)->price,
            "offer_item_flat_price"     => AppLibrary::flatAmountFormat(optional($this->item)->price),
            "offer_item_convert_price"  => AppLibrary::convertAmountFormat(optional($this->item)->price),
            "offer_item_currency_price" => AppLibrary::currencyAmountFormat(optional($this->item)->price),
            'offer_item_status'         => optional($this->item)->status,
        ];
    }
}
