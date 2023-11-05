<?php

namespace App\Services;

use Smartisan\Settings\Facades\Settings;

class SettingService
{
    public function list() : array
    {
        $array = [];
        $array = array_merge($array, Settings::group('company')->all());
        $array = array_merge($array, Settings::group('site')->all());
        $array = array_merge($array, Settings::group('theme')->all());
        $array = array_merge($array, Settings::group('otp')->all());
        $array = array_merge($array, Settings::group('social_media')->all());
        $array = array_merge($array, Settings::group('order_setup')->all());
        $array = array_merge($array, Settings::group('notification')->all());
        return array_merge($array, Settings::group('cookies')->all());
    }
}
