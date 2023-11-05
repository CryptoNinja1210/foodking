<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Bkash extends FormRequest
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
        if (request()->bkash_status == Activity::ENABLE) {
            return [
                'bkash_app_key'    => ['required', 'string'],
                'bkash_app_secret' => ['required', 'string'],
                'bkash_username'   => ['required', 'string'],
                'bkash_password'   => ['required', 'string'],
                'bkash_mode'       => ['required', 'string'],
                'bkash_status'    => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'bkash_app_key'    => ['nullable', 'string'],
                'bkash_app_secret' => ['nullable', 'string'],
                'bkash_username'   => ['nullable', 'string'],
                'bkash_password'   => ['nullable', 'string'],
                'bkash_mode'       => ['nullable', 'string'],
                'bkash_status'     => ['nullable', 'numeric'],
            ];
        }
    }
}
