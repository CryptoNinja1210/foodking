<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Services\ThemeService;
use App\Http\Requests\ThemeRequest;
use App\Http\Resources\ThemeResource;

class ThemeController extends AdminController
{
    public ThemeService $themeService;

    public function __construct(ThemeService $themeService)
    {
        parent::__construct();
        $this->themeService = $themeService;

        $this->middleware(['permission:settings'])->only('update');
    }

    public function index()
    {
        try {
            return new ThemeResource($this->themeService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(ThemeRequest $request)
    {
        try {
            return new ThemeResource($this->themeService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
