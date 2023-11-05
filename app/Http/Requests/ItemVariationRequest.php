<?php

namespace App\Http\Requests;

use App\Libraries\AppLibrary;
use App\Rules\IniAmount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemVariationRequest extends FormRequest
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
            'name'              => [
                'required',
                'string',
                'max:190',
                Rule::unique("item_variations", "name")->whereNull('deleted_at')->ignore($this->route('itemVariation.id'))->where(
                    'item_id',
                    $this->route('item.id')
                )
            ],
            'item_attribute_id' => ['required', 'numeric'],
            'price'             => ['required', new IniAmount(true)],
            'caution'           => ['nullable', 'string', 'max:5000'],
            'status'            => ['required', 'numeric', 'max:24'],
        ];
    }
}