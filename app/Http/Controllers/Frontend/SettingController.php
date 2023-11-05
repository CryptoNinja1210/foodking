<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Resources\SettingResource;
use App\Services\SettingService;
use Exception;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    private SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index() : \Illuminate\Http\Response | SettingResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new SettingResource($this->settingService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
