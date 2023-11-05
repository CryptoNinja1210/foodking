<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Paystack extends FormRequest
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
        if (request()->paystack_status == Activity::ENABLE) {
            return [
                'paystack_public_key'  => ['required', 'string'],
                'paystack_secret_key'  => ['required', 'string'],
                'paystack_payment_url' => ['required', 'string'],
                'paystack_mode'        => ['required', 'string'],
                'paystack_status'      => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'paystack_public_key'  => ['nullable', 'string'],
                'paystack_secret_key'  => ['nullable', 'string'],
                'paystack_payment_url' => ['nullable', 'string'],
                'paystack_mode'        => ['nullable', 'string'],
                'paystack_status'      => ['nullable', 'numeric'],
            ];
        }
    }
}
