<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Mollie extends FormRequest
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
        if (request()->mollie_status == Activity::ENABLE) {
            return [
                'mollie_api_key' => ['required', 'string'],
                'mollie_mode'    => ['required', 'string'],
                'mollie_status'  => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'mollie_api_key' => ['nullable', 'string'],
                'mollie_mode'    => ['nullable', 'string'],
                'mollie_status'  => ['nullable', 'numeric'],
            ];
        }
    }
}