<?php

namespace App\Services;


use App\Enums\Activity;
use App\Http\Requests\SiteRequest;
use App\Models\Currency;
use Dipokhalder\EnvEditor\EnvEditor;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class SiteService
{
    public $envService;

    public function __construct(EnvEditor $envEditor)
    {
        $this->envService = $envEditor;
    }

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            return Settings::group('site')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(SiteRequest $request)
    {
        try {
            $currency = Currency::find($request->site_default_currency);
            Settings::group('site')->set($request->validated() + ['site_default_currency_symbol' => $currency->symbol]);

            $this->envService->addData([
                'APP_DEBUG'              => $request->site_app_debug == Activity::ENABLE ? 'true' : 'false',
                'TIMEZONE'               => $request->site_default_timezone,
                'CURRENCY'               => $currency?->code,
                'CURRENCY_SYMBOL'        => $currency?->symbol,
                'CURRENCY_POSITION'      => $request->site_currency_position,
                'CURRENCY_DECIMAL_POINT' => $request->site_digit_after_decimal_point,
                'DATE_FORMAT'            => $request->site_date_format,
                'TIME_FORMAT'            => $request->site_time_format
            ]);

            if (!$this->envService->getValue('DEMO')) {
                $this->envService->addData([
                    'MIX_GOOGLE_MAP_KEY'     => $request->site_google_map_key,
                ]);
            }

            Artisan::call('optimize:clear');
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
