<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\TimezoneResource;
use App\Services\TimezoneService;
use Exception;

class TimezoneController extends AdminController
{
    private TimezoneService $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        parent::__construct();
        $this->timezoneService = $timezoneService;
    }

    public function index(
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return TimezoneResource::collection($this->timezoneService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
