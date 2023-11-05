<?php

namespace App\Http\Requests;


use App\Enums\Ask;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['nullable', 'string', 'email', 'max:255', Rule::unique("users", "email")->whereNull('deleted_at')->where('is_guest', Ask::NO)],
            'phone'        => ['required', 'numeric', Rule::unique("users", "phone")->whereNull('deleted_at')->where('is_guest', Ask::NO)],
            'country_code' => ['required', 'numeric'],
            'password'     => ['required', 'string', 'min:6'],
        ];
    }
}
