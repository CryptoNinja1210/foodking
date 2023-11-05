<?php

namespace App\Services;


use Exception;
use Illuminate\Support\Facades\Log;
use PragmaRX\Countries\Package\Countries;

class CountryCodeService
{

    /**
     * @throws Exception
     */
    public function list(): array
    {
        try {
            $countryArray = [];
            $countries    = Countries::all();
            foreach ($countries as $key => $country) {
                $countryArray[] = (object)[
                    'country_code' => $key,
                    'country_name' => $country['admin'] . ' (' . $key . ')',
                ];
            }
            return ['data' => $countryArray];
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show($country)
    {
        try {
            return Countries::where('cca3', $country)->first();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
