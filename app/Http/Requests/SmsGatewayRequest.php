<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsGatewayRequest extends FormRequest
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
            'analytic_'.request()->SmsGateway                   => ['required', 'string', 'max:50'],
            'analytic_'.request()->SmsGateway.'_description'    => ['required', 'string'],
            'analytic_'.request()->SmsGateway.'_status'         => ['required', 'numeric'],
        ];
    }
}
