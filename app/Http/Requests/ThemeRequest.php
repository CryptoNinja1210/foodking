<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemeRequest extends FormRequest
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
            'theme_logo'         => ['nullable', 'file', 'mimes:jpg,jpeg,png'],
            'theme_favicon_logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png'],
            'theme_footer_logo'  => ['nullable', 'file', 'mimes:jpg,jpeg,png']
        ];
    }
}