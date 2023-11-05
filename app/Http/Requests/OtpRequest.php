<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpRequest extends FormRequest
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
            'otp_type'        => 'required|string',
            'otp_digit_limit' => 'required|numeric',
            'otp_expire_time' => 'required|numeric|min:1|max:60',
        ];
    }
}
