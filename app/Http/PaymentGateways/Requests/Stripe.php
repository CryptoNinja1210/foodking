<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Stripe extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        if (request()->stripe_status == Activity::ENABLE) {
            return [
                'stripe_key'    => ['required', 'string'],
                'stripe_secret' => ['required', 'string'],
                'stripe_mode'   => ['required', 'string'],
                'stripe_status' => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'stripe_key'    => ['nullable', 'string'],
                'stripe_secret' => ['nullable', 'string'],
                'stripe_mode'   => ['nullable', 'string'],
                'stripe_status' => ['nullable', 'numeric'],
            ];
        }
    }
}
