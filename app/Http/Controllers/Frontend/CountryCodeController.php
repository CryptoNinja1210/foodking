<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\CountryCodeService;
use App\Http\Resources\CountryCodeResource;

class CountryCodeController extends Controller
{
    public CountryCodeService $countryCodeService;

    public function __construct(CountryCodeService $countryCodeService)
    {
        $this->countryCodeService = $countryCodeService;
    }

    public function index(
    ) : \Illuminate\Http\Response | array | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return $this->countryCodeService->list();
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show($country
    ) : \Illuminate\Http\Response | CountryCodeResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new CountryCodeResource($this->countryCodeService->show($country));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
