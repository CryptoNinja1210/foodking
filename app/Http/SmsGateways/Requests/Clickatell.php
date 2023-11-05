<?php

namespace App\Http\SmsGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Clickatell extends FormRequest
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
        if (request()->clickatell_status == Activity::ENABLE) {
            return [
                'clickatell_apikey' => ['required', 'string'],
                'clickatell_status' => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'clickatell_apikey' => ['nullable', 'string'],
                'clickatell_status' => ['nullable', 'string'],
            ];
        }
    }
}
