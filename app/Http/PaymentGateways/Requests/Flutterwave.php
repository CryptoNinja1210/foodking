<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Flutterwave extends FormRequest
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
        if (request()->flutterwave_status == Activity::ENABLE) {
            return [
                'flutterwave_public_key' => ['required', 'string'],
                'flutterwave_secret_key' => ['required', 'string'],
                'flutterwave_mode'       => ['required', 'string'],
                'flutterwave_status'     => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'flutterwave_public_key' => ['nullable', 'string'],
                'flutterwave_secret_key' => ['nullable', 'string'],
                'flutterwave_mode'       => ['nullable', 'string'],
                'flutterwave_status'     => ['nullable', 'numeric'],
            ];
        }
    }
}
