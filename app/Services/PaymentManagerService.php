<?php

namespace App\Services;


class PaymentManagerService
{

    public object $gateway;

    public function gateway(...$args) : static
    {
        $paymentMethod = '';
        if (count($args) > 0) {
            $paymentMethod = ucfirst(array_shift($args));
        }

        if (count($args) == 0) {
            $args = null;
        }

        $className     = 'App\\Http\\PaymentGateways\\Gateways\\' . $paymentMethod;
        $this->gateway = new $className($args);
        return $this;
    }

    public function payment($order, $request)
    {
        return $this->gateway->payment($order, $request);
    }

    public function status()
    {
        return $this->gateway->status();
    }

    public function success($order, $request)
    {
        return $this->gateway->success($order, $request);
    }

    public function fail($order, $request)
    {
        return $this->gateway->fail($order, $request);
    }

    public function cancel($order, $request)
    {
        return $this->gateway->cancel($order, $request);
    }

}
