<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Paypal extends FormRequest
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
        if (request()->paypal_status == Activity::ENABLE) {
            return [
                'paypal_app_id'        => ['required', 'string'],
                'paypal_mode'          => ['required', 'string'],
                'paypal_client_id'     => ['required', 'string'],
                'paypal_client_secret' => ['required', 'string'],
                'paypal_status'        => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'paypal_app_id'        => ['nullable', 'string'],
                'paypal_mode'          => ['nullable', 'string'],
                'paypal_client_id'     => ['nullable', 'string'],
                'paypal_client_secret' => ['nullable', 'string'],
                'paypal_status'        => ['nullable', 'numeric'],
            ];
        }
    }
}
