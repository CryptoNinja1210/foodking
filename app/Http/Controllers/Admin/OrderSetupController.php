<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderSetupRequest;
use App\Http\Resources\OrderSetupResource;
use App\Services\OrderSetupService;
use Exception;

class OrderSetupController extends AdminController
{
    public OrderSetupService $orderSetupService;

    public function __construct(OrderSetupService $orderSetupService)
    {
        parent::__construct();
        $this->orderSetupService = $orderSetupService;
        $this->middleware(['permission:settings'])->only('update');
    }

    public function index(
    ) : \Illuminate\Http\Response | OrderSetupResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OrderSetupResource($this->orderSetupService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(OrderSetupRequest $request
    ) : \Illuminate\Http\Response | OrderSetupResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new OrderSetupResource($this->orderSetupService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
