<?php

namespace App\Services;

use App\Http\Requests\CookiesRequest;
use App\Http\Requests\CookiesSetRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class CookiesService
{

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            return Settings::group('cookies')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @param CookiesRequest $request
     * @return
     * @throws Exception
     */
    public function update(CookiesRequest $request)
    {
        try {
            Settings::group('cookies')->set($request->validated());
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    public function get(Request $request)
    {
        return [
            'cookies_notification' => $request->cookie('cookies_notification'),
            'user_cookie_consent'  => $request->cookie('user_cookie_consent')
        ];
    }

    public function set(CookiesSetRequest $cookiesSetRequest)
    {
        $cookiesConsent = '';
        if ($cookiesSetRequest->cookies_notification) {
            $cookiesConsent = $_SERVER['HTTP_USER_AGENT'] . ', ' . $cookiesSetRequest->ip();
        }
        setcookie('user_cookie_consent', $cookiesConsent, time() + (86400 * 7), "/");
        setcookie('cookies_notification', true, time() + (86400 * 7), "/");
        return ['cookies_notification' => true, 'user_cookie_consent' => $cookiesConsent];
    }
}
