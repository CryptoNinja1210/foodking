<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Paytm extends FormRequest
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
        if (request()->paytm_status == Activity::ENABLE) {
            return [
                'paytm_merchant_id'      => ['required', 'string'],
                'paytm_merchant_key'     => ['required', 'string'],
                'paytm_merchant_website' => ['required', 'string'],
                'paytm_channel'          => ['required', 'string'],
                'paytm_industry_type'    => ['required', 'string'],
                'paytm_mode'             => ['required', 'string'],
                'paytm_status'           => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'paytm_merchant_id'      => ['nullable', 'string'],
                'paytm_merchant_key'     => ['nullable', 'string'],
                'paytm_merchant_website' => ['nullable', 'string'],
                'paytm_channel'          => ['nullable', 'string'],
                'paytm_industry_type'    => ['nullable', 'string'],
                'paytm_mode'             => ['nullable', 'string'],
                'paytm_status'           => ['nullable', 'numeric'],
            ];
        }
    }
}