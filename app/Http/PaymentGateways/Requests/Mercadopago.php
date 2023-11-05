<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Mercadopago extends FormRequest
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
        if (request()->mercadopago_status == Activity::ENABLE) {
            return [
                'mercadopago_client_id'     => ['required', 'string'],
                'mercadopago_client_secret' => ['required', 'string'],
                'mercadopago_mode'          => ['required', 'string'],
                'mercadopago_status'        => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'mercadopago_client_id'     => ['nullable', 'string'],
                'mercadopago_client_secret' => ['nullable', 'string'],
                'mercadopago_mode'          => ['nullable', 'string'],
                'mercadopago_status'        => ['nullable', 'numeric'],
            ];
        }
    }
}
