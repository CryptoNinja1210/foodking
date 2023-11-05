<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OtpRequest;
use App\Http\Resources\OtpResource;
use App\Services\OtpService;
use Exception;

class OtpController extends AdminController
{
    private OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        parent::__construct();
        $this->otpService = $otpService;

        $this->middleware(['permission:settings'])->only('update');
    }

    public function index(): \Illuminate\Http\Response | OtpResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OtpResource($this->otpService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(OtpRequest $request)
    {
        try {
            return new OtpResource($this->otpService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
