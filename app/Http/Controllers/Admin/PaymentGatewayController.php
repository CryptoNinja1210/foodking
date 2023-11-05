<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PaymentGatewayResource;
use App\Services\PaymentGatewayService;
use Exception;
use Illuminate\Http\Request;


class PaymentGatewayController extends AdminController
{
    private PaymentGatewayService $paymentGatewayService;

    public function __construct(PaymentGatewayService $paymentGatewayService)
    {
        parent::__construct();
        $this->paymentGatewayService = $paymentGatewayService;
        $this->middleware(['permission:settings'])->only('update');
    }

    public function index(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return PaymentGatewayResource::collection($this->paymentGatewayService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(
        Request $request
    ): PaymentGatewayResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        $className          = 'App\\Http\\PaymentGateways\\Requests\\' . ucfirst($request->payment_type);
        $gateway            = new $className;
        $validationRequests = $request->validate($gateway->rules());

        try {
            return new PaymentGatewayResource($this->paymentGatewayService->update($validationRequests));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
