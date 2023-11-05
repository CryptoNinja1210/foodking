<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OfferItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [

            'item_id'   => [
                'required',
                'numeric',
                Rule::unique("offer_items", "item_id")->ignore($this->route('offerItem.id'))->where('offer_id', $this->route('offer.id')),
            ],
        ];
    }
}
