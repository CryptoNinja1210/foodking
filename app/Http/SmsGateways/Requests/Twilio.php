<?php

namespace App\Http\SmsGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Twilio extends FormRequest
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
        if (request()->twilio_status == Activity::ENABLE) {
            return [
                'twilio_account_sid' => ['required', 'string'],
                'twilio_auth_token'  => ['required', 'string'],
                'twilio_from'        => ['required', 'string'],
                'twilio_status'      => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'twilio_account_sid' => ['nullable', 'string'],
                'twilio_auth_token'  => ['nullable', 'string'],
                'twilio_from'        => ['nullable', 'string'],
                'twilio_status'      => ['nullable', 'numeric'],
            ];
        }
    }
}
