<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
                'max:190',
                Rule::unique("pages", "title")->ignore($this->route('page.id'))
            ],
            'description'     => ['required', 'string'],
            'menu_section_id' => ['required', 'numeric'],
            'template_id'     => ['nullable', 'numeric'],
            'status'          => ['required', 'numeric', 'max:24'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ];
    }
}
