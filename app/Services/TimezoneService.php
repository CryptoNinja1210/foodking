<?php

namespace App\Services;

use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Log;

class TimezoneService
{
    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            $timezones     = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
            $timezoneArray = [];
            $i             = 1;
            foreach ($timezones as $timezone) {
                $timezoneArray[] = (object)[
                    'id'   => $i,
                    'name' => $timezone
                ];
                $i++;
            }
            return collect($timezoneArray);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
