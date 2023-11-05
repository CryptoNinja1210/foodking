<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description === null ? '' : $this->description,
            'code'        => $this->code,

            'discount'          => $this->discount === null ? 0 : $this->discount,
            "flat_discount"     => AppLibrary::flatAmountFormat($this->discount),
            "convert_discount"  => AppLibrary::convertAmountFormat($this->discount),
            "currency_discount" => AppLibrary::currencyAmountFormat($this->discount),

            'discount_type'      => $this->discount_type === null ? 5 : $this->discount_type,
            'convert_start_date' => AppLibrary::datetime($this->start_date),
            'convert_end_date'   => AppLibrary::datetime($this->end_date),
            'start_date'         => !blank($this->start_date) ? $this->start_date : "",
            'end_date'           => !blank($this->end_date) ? $this->end_date : "",

            'minimum_order'                 => $this->minimum_order === null ? 0 : $this->minimum_order,
            "minimum_order_flat_amount"     => AppLibrary::flatAmountFormat($this->minimum_order),
            "minimum_order_convert_amount"  => AppLibrary::convertAmountFormat($this->minimum_order),
            "minimum_order_currency_amount" => AppLibrary::currencyAmountFormat($this->minimum_order),

            'maximum_discount'          => $this->maximum_discount === null ? 0 : $this->maximum_discount,
            "maximum_flat_discount"     => AppLibrary::flatAmountFormat($this->maximum_discount),
            "maximum_convert_discount"  => AppLibrary::convertAmountFormat($this->maximum_discount),
            "maximum_currency_discount" => AppLibrary::currencyAmountFormat($this->maximum_discount),

            'limit_per_user' => $this->limit_per_user === null ? 0 : $this->limit_per_user,
            "image"          => $this->image,
        ];
    }
}
