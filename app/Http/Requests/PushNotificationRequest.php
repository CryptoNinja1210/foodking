<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PushNotificationRequest extends FormRequest
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
            'title'        => [
                'required',
                'string',
                'max:255',
            ],
            'description' => ['required', 'string', 'max:2000'],
            'role_id'     => ['nullable', 'numeric'],
            'user_id'     => ['nullable', 'numeric'],
            'branch_id'   => ['required', 'numeric'],
            'image'       => ['image', 'mimes:jpeg,png,jpg|max:5098']

        ];
    }
}
