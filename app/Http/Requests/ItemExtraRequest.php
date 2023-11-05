<?php

namespace App\Http\Requests;

use App\Rules\IniAmount;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemExtraRequest extends FormRequest
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
            'name'        => [
                'required',
                'string',
                'max:190',
                Rule::unique("item_extras", "name")->whereNull('deleted_at')->ignore($this->route('itemExtra.id'))->where('item_id', $this->route('item.id')),
            ],
            'price'   => ['required', new IniAmount()],
            'status'  => ['required', 'numeric', 'max:24'],
        ];
    }
}