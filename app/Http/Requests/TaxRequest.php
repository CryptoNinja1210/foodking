<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxRequest extends FormRequest
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
                'max:190'
            ],
            'code'              => [
                'required',
                'string',
                'max:20',
                Rule::unique("taxes", "code")->whereNull('deleted_at')->ignore($this->route('tax.id'))
            ],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:9999999999999'],
            'status'   => ['required', 'numeric', 'max:24'],
        ];
    }
}
