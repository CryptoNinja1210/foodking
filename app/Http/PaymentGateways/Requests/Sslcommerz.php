<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Sslcommerz extends FormRequest
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
        if (request()->sslcommerz_status == Activity::ENABLE) {
            return [
                'sslcommerz_store_name'     => ['required', 'string'],
                'sslcommerz_store_id'       => ['required', 'string'],
                'sslcommerz_store_password' => ['required', 'string'],
                'sslcommerz_mode'           => ['required', 'string'],
                'sslcommerz_status'         => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'sslcommerz_store_name'     => ['nullable', 'string'],
                'sslcommerz_store_id'       => ['nullable', 'string'],
                'sslcommerz_store_password' => ['nullable', 'string'],
                'sslcommerz_mode'           => ['nullable', 'string'],
                'sslcommerz_status'         => ['nullable', 'numeric'],
            ];
        }
    }
}
