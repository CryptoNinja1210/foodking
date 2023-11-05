<?php

namespace App\Http\Resources;

use App\Enums\Ask;
use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'id'                                  => $this->id,
            'order_serial_no'                     => $this->order_serial_no,
            'token'                               => $this->token,
            "subtotal_currency_price"             => AppLibrary::currencyAmountFormat($this->subtotal),
            "subtotal_without_tax_currency_price" => AppLibrary::currencyAmountFormat($this->subtotal - $this->total_tax),
            "discount_currency_price"             => AppLibrary::currencyAmountFormat($this->discount),
            "delivery_charge_currency_price"      => AppLibrary::currencyAmountFormat($this->delivery_charge),
            "total_currency_price"                => AppLibrary::currencyAmountFormat($this->total),
            "total_tax_currency_price"            => AppLibrary::currencyAmountFormat($this->total_tax),
            'order_type'                          => $this->order_type,
            'order_datetime'                      => AppLibrary::datetime($this->order_datetime),
            'order_date'                          => AppLibrary::date($this->order_datetime),
            'order_time'                          => AppLibrary::time($this->order_datetime),
            'delivery_date'                       => $this->is_advance_order == Ask::YES ? AppLibrary::increaseDate($this->order_datetime, 1) : AppLibrary::date($this->order_datetime),
            'delivery_time'                       => AppLibrary::deliveryTime($this->delivery_time),
            'payment_method'                      => $this->payment_method,
            'payment_status'                      => $this->payment_status,
            'is_advance_order'                    => $this->is_advance_order,
            'preparation_time'                    => $this->preparation_time,
            'status'                              => $this->status,
            'status_name'                         => trans('orderStatus.' . $this->status),
            'reason'                              => $this->reason,
            'user'                                => new UserResource($this->user),
            'order_address'                       => new AddressResource($this->address),
            'branch'                              => new BranchResource($this->branch),
            'delivery_boy'                        => new UserResource($this->deliveryBoy),
            'coupon'                              => new CouponResource($this->coupon),
            'transaction'                         => new TransactionResource($this->transaction),
            'order_items'                         => OrderItemResource::collection($this->orderItems),
        ];
    }
}
