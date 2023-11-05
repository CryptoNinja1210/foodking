<?php

namespace App\Http\SmsGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Nexmo extends FormRequest
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
        if (request()->nexmo_status == Activity::ENABLE) {
            return [
                'nexmo_key'    => ['required', 'string'],
                'nexmo_secret' => ['required', 'string'],
                'nexmo_status' => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'nexmo_key'    => ['nullable', 'string'],
                'nexmo_secret' => ['nullable', 'string'],
                'nexmo_status' => ['nullable', 'numeric'],
            ];
        }
    }
}
