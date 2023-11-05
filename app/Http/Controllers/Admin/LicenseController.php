<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LicenseRequest;
use App\Http\Resources\LicenseResource;
use App\Services\LicenseService;
use Exception;

class LicenseController extends AdminController
{
    public LicenseService $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        parent::__construct();
        $this->licenseService = $licenseService;
        $this->middleware(['permission:settings'])->only('update');
    }

    public function index(): \Illuminate\Http\Response | LicenseResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new LicenseResource($this->licenseService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(LicenseRequest $request): \Illuminate\Http\Response | LicenseResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new LicenseResource($this->licenseService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
