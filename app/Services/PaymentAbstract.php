<?php

namespace App\Services;

abstract class PaymentAbstract
{

    public object $gateway;
    public object $paymentGateway;
    public object $paymentGatewayOption;
    public PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    abstract public function status();

    abstract public function payment($order, $request);

    abstract public function success($order, $request);

    abstract public function fail($order, $request);

    abstract public function cancel($order, $request);
}
