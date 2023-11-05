<?php

namespace App\Http\Requests;


use App\Enums\Ask;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignupPhoneRequest extends FormRequest
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
            'phone' => ['required', 'string', 'max:190', Rule::unique("users", "phone")->whereNull('deleted_at')->where('is_guest', Ask::NO)],
            'code'  => ['required', 'string'],
        ];
    }
}
