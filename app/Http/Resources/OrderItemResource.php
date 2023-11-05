<?php

namespace App\Http\Resources;

use App\Enums\TaxType;
use App\Models\Currency;
use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;
use Smartisan\Settings\Facades\Settings;

class OrderItemResource extends JsonResource
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
            'id'                               => $this->id,
            'order_id'                         => $this->order_id,
            'branch_id'                        => $this->branch_id,
            'item_id'                          => $this->orderItem?->id,
            'item_name'                        => $this->orderItem?->name,
            'item_image'                       => $this->orderItem?->thumb,
            'quantity'                         => $this->quantity,
            'discount'                         => AppLibrary::currencyAmountFormat($this->discount),
            'price'                            => AppLibrary::currencyAmountFormat($this->price),
            'item_variations'                  => json_decode($this->item_variations),
            'item_extras'                      => json_decode($this->item_extras),
            'item_variation_currency_total'    => AppLibrary::currencyAmountFormat($this->item_variation_total),
            'item_extra_currency_total'        => AppLibrary::currencyAmountFormat($this->item_extra_total),
            'total_convert_price'              => AppLibrary::convertAmountFormat($this->total_price),
            'total_currency_price'             => AppLibrary::currencyAmountFormat($this->total_price),
            'instruction'                      => $this->instruction,
            'tax_type'                         => $this->tax_type === TaxType::FIXED ? env('CURRENCY') : '%',
            'tax_rate'                         => $this->tax_rate,
            'tax_currency_rate'                => AppLibrary::flatAmountFormat($this->tax_rate),
            'tax_name'                         => $this->tax_name,
            'tax_currency_amount'              => AppLibrary::currencyAmountFormat($this->tax_amount),
            'total_without_tax_currency_price' => AppLibrary::currencyAmountFormat($this->total_price - $this->tax_amount),
        ];
    }
}
