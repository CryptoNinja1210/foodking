<?php

namespace App\Http\SmsGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Msg91 extends FormRequest
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
        if (request()->msg91_status == Activity::ENABLE) {
            return [
                'msg91_key'       => ['required', 'string'],
                'msg91_sender_id' => ['required', 'string'],
                'msg91_status'    => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'msg91_key'       => ['nullable', 'string'],
                'msg91_sender_id' => ['nullable', 'string'],
                'msg91_status'    => ['nullable', 'numeric'],
            ];
        }
    }
}
