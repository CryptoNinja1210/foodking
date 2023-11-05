<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MailRequest extends FormRequest
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
            'mail_host'       => ['required', 'string', 'max:190'],
            'mail_port'       => ['required', 'string', 'max:190'],
            'mail_username'   => ['required', 'string', 'max:190'],
            'mail_password'   => ['required', 'string', 'max:190'],
            'mail_encryption' => ['required', 'string', 'max:190'],
            'mail_from_name'  => ['required', 'string', 'max:190'],
            'mail_from_email' => ['required', 'string', 'max:190'],
        ];
    }
}
