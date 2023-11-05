<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Senangpay extends FormRequest
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
        if (request()->senangpay_status == Activity::ENABLE) {
            return [
                'senangpay_merchant_id' => ['required', 'string'],
                'senangpay_secret_key'  => ['required', 'string'],
                'senangpay_mode'        => ['required', 'string'],
                'senangpay_status'      => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'senangpay_merchant_id' => ['nullable', 'string'],
                'senangpay_secret_key'  => ['nullable', 'string'],
                'senangpay_mode'        => ['nullable', 'string'],
                'senangpay_status'      => ['nullable', 'numeric'],
            ];
        }
    }
}