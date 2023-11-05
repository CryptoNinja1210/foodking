<?php

namespace App\Traits;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Smartisan\Settings\Facades\Settings;


trait MultiTenantModelTrait
{
    public static function bootMultiTenantModelTrait()
    {
        if (!App::runningInConsole() && Auth::check()) {

        }
    }
}
