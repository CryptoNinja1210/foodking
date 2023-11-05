<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LanguageRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                'string',
                'max:190',
                Rule::unique("languages", "name")->ignore($this->route('language.id'))
            ],

            'code'   => ['required', 'string', 'max:20'],
            'status' => ['required', 'numeric', 'max:24'],
        ];
    }
}
