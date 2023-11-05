<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
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
            "id"                => $this->id,
            "name"              => $this->name,
            "name_symbol"       => $this->name . ' (' . $this->symbol . ')',
            "symbol"            => $this->symbol,
            "code"              => $this->code,
            "is_cryptocurrency" => $this->is_cryptocurrency,
            "exchange_rate"     => AppLibrary::convertAmountFormat($this->exchange_rate)
        ];
    }
}
