<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
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
            'site_date_format'               => ['required', 'string', 'max:190'],
            'site_time_format'               => ['required', 'string', 'max:190'],
            'site_default_timezone'          => ['required', 'string', 'max:190'],
            'site_default_branch'            => ['required', 'numeric'],
            'site_default_currency'          => ['required', 'numeric'],
            'site_currency_position'         => ['required', 'numeric'],
            'site_digit_after_decimal_point' => ['required', 'numeric', 'max:6'],
            'site_email_verification'        => ['required', 'numeric'],
            'site_phone_verification'        => ['required', 'numeric'],
            'site_default_language'          => ['required', 'numeric'],
            'site_language_switch'           => ['required', 'numeric'],
            'site_app_debug'                 => ['required', 'numeric'],
            'site_auto_update'               => ['required', 'numeric'],
            'site_google_map_key'            => ['required', 'string', 'max:190'],
            'site_android_app_link'          => ['nullable', 'string', 'max:190'],
            'site_ios_app_link'              => ['nullable', 'string', 'max:190'],
            'site_copyright'                 => ['required', 'string', 'max:190'],
            'site_online_payment_gateway'    => ['required', 'numeric'],
            'site_default_sms_gateway'       => ['nullable', 'numeric'],
        ];
    }
}
