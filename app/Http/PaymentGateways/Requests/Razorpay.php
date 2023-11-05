<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Razorpay extends FormRequest
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
        if (request()->razorpay_status == Activity::ENABLE) {
            return [
                'razorpay_key'    => ['required', 'string'],
                'razorpay_secret' => ['required', 'string'],
                'razorpay_status' => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'razorpay_key'    => ['nullable', 'string'],
                'razorpay_secret' => ['nullable', 'string'],
                'razorpay_status' => ['nullable', 'numeric'],
            ];
        }
    }
}
