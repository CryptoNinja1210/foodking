<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdministratorAddressRequest extends FormRequest
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
            'label'     => ['required', 'string', 'max:190', Rule::unique("addresses", "label")->ignore($this->route('address.id'))->where('user_id', $this->route('administrator.id'))],
            'latitude'  => ['required', 'max:190'],
            'longitude' => ['required', 'max:190'],
            'address'   => ['required', 'string', 'max:500'],
            'apartment' => ['nullable', 'string', 'max:200'],
        ];
    }
}