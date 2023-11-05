<?php

namespace App\Services;


use App\Models\SmsGateway;
use Smartisan\Settings\Facades\Settings;


class SmsService
{
    public string $gateway;

    public function gateway() : string
    {
        $this->gateway = 'twilio';
        $gatewayId     = Settings::group('site')->get('site_default_sms_gateway');
        if ($gatewayId) {
            $gateway       = SmsGateway::find($gatewayId);
            $this->gateway = $gateway->slug;
        }
        return $this->gateway;
    }
}
