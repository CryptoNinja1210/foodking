<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Cashfree extends FormRequest
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
        if (request()->cashfree_status == Activity::ENABLE) {
            return [
                'cashfree_app_id'     => ['required', 'string'],
                'cashfree_secret_key' => ['required', 'string'],
                'cashfree_mode'       => ['required', 'string'],
                'cashfree_status'     => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'cashfree_app_id'     => ['nullable', 'string'],
                'cashfree_secret_key' => ['nullable', 'string'],
                'cashfree_mode'       => ['nullable', 'string'],
                'cashfree_status'     => ['nullable', 'numeric'],
            ];
        }
    }
}