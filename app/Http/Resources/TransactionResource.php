<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'id'              => $this->id,
            'order_id'        => $this->order_id,
            'order_serial_no' => $this->order?->order_serial_no,
            'transaction_no'  => $this->transaction_no,
            'amount'          => AppLibrary::flatAmountFormat($this->amount),
            'payment_method'  => strtoupper($this->payment_method),
            'type'            => $this->type,
            'sign'            => $this->sign,
            'date'            => AppLibrary::datetime($this->created_at)
        ];
    }
}
