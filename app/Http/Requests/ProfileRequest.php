<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
    public function rules()
    {
        return [
            'first_name'   => ['required', 'string', 'max:190'],
            'last_name'    => ['required', 'string', 'max:190'],
            'email'        => [
                'required',
                'email',
                'max:190',
                Rule::unique("users", "email")->ignore(auth()->user()->id)
            ],
            'phone'        => ['required', 'string', 'max:20', Rule::unique("users", "phone")->ignore(auth()->user()->id)],
            'country_code' => ['required', 'string', 'max:20'],
        ];
    }
}
