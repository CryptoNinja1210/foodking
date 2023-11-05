<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeSlotRequest extends FormRequest
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

        return [
            'opening_time' => ['required', 'string', 'date_format:H:i', 'max:24'],
            'closing_time' => ['required', 'string', 'date_format:H:i', 'after:opening_time', 'max:24'],
            'day'          => ['required', 'numeric', 'digits_between:1,7'],
        ];
    }
}
