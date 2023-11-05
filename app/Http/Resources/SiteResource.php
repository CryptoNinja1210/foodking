<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
{

    public $info;

    public function __construct($info)
    {
        parent::__construct($info);
        $this->info = $info;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "site_date_format"               => $this->info['site_date_format'],
            "site_time_format"               => $this->info['site_time_format'],
            "site_default_timezone"          => $this->info['site_default_timezone'],
            "site_default_branch"            => $this->info['site_default_branch'],
            "site_default_currency"          => $this->info['site_default_currency'],
            "site_default_currency_symbol"   => $this->info['site_default_currency_symbol'],
            "site_currency_position"         => $this->info['site_currency_position'],
            "site_digit_after_decimal_point" => $this->info['site_digit_after_decimal_point'],
            "site_email_verification"        => $this->info['site_email_verification'],
            "site_phone_verification"        => $this->info['site_phone_verification'],
            "site_default_language"          => $this->info['site_default_language'],
            "site_language_switch"           => $this->info['site_language_switch'],
            "site_app_debug"                 => $this->info['site_app_debug'],
            "site_auto_update"               => $this->info['site_auto_update'],
            "site_google_map_key"            => $this->info['site_google_map_key'],
            "site_android_app_link"          => $this->info['site_android_app_link'],
            "site_ios_app_link"              => $this->info['site_ios_app_link'],
            "site_copyright"                 => $this->info['site_copyright'],
            "site_online_payment_gateway"    => $this->info['site_online_payment_gateway'],
            "site_default_sms_gateway"       => $this->info['site_default_sms_gateway'],
        ];
    }
}