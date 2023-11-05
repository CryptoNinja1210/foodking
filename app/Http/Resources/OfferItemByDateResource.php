<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use App\Models\OfferItem;
use Illuminate\Http\Resources\Json\JsonResource;
use IlluminateAgnostic\Arr\Support\Arr;

class OfferItemByDateResource extends JsonResource
{


    public function toArray($request) : array
    {
        $items = [];
        foreach ($this as $offers) {
            if (count($offers) > 0) {
                foreach ($offers as $offer) {
                    if (count($offer->offerItems) > 0) {
                        foreach ($offer->offerItems as $item) {
                            $itemPrice = ($item->item?->price - ($item->item?->price / 100 * ($offer->amount)));
                            $items[]   = [
                                "id"                                 => $item->item?->id,
                                "offer_id"                           => $offer->id,
                                "item_price_after_flat_discount"     => AppLibrary::flatAmountFormat($itemPrice),
                                "item_price_after_convert_discount"  => AppLibrary::convertAmountFormat($itemPrice),
                                "item_price_after_currency_discount" => AppLibrary::currencyAmountFormat($itemPrice),
                                "convert_discount"                   => AppLibrary::convertAmountFormat($offer->amount),
                            ];
                        }
                    }
                }
            }
        }
        return $items;
    }
}
