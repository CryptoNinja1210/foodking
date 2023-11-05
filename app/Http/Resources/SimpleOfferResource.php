<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleOfferResource extends JsonResource
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
            'name'           => $this->name,
            'amount'         => $this->amount === null ? 0 : $this->amount,
            'flat_price'     => $this->flat_price,
            'convert_price'  => $this->convert_price,
            'currency_price' => $this->currency_price
        ];
    }
}
