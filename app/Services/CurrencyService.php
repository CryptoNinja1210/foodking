<?php

namespace App\Services;


use Exception;
use App\Models\Currency;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CurrencyRequest;
use App\Http\Requests\PaginateRequest;
use Smartisan\Settings\Facades\Settings;

class CurrencyService
{
    protected $currencyFilter = [
        'name',
        'symbol',
        'code',
        'is_cryptocurrency',
        'exchange_rate'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return Currency::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->currencyFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(CurrencyRequest $request)
    {
        try {
            return Currency::create($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(CurrencyRequest $request, Currency $currency)
    {
        try {
            return tap($currency)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Currency $currency): void
    {
        try {
            if (Settings::group('site')->get("site_default_currency") != $currency->id) {
                $currency->delete();
            } else {
                throw new Exception("Default currency not deletable", 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
