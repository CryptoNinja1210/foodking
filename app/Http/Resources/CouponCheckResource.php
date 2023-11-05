<?php

namespace App\Http\Resources;

use App\Enums\DiscountType;
use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponCheckResource extends JsonResource
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
            'id'                => $this->id,
            'code'              => $this->code,
            'discount'          => $this->amount($request),
            "flat_discount"     => AppLibrary::flatAmountFormat($this->amount($request)),
            "convert_discount"  => AppLibrary::convertAmountFormat($this->amount($request)),
            "currency_discount" => AppLibrary::currencyAmountFormat($this->amount($request)),
        ];
    }

    public function amount($request)
    {
        if ($this->discount_type == DiscountType::FIXED) {
            $amount = $this->discount;
            if ($amount > $this->maximum_discount) {
                return $this->maximum_discount;
            } else {
                return $amount;
            }
        } else {
            $amount = ($request->total * ($this->discount) / 100);
            if ($amount > $this->maximum_discount) {
                return $this->maximum_discount;
            } else {
                return $amount;
            }
        }
    }
}
