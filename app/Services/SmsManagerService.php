<?php

namespace App\Services;


class SmsManagerService
{

    public object $gateway;

    public function gateway(...$args) : static
    {
        $smsMethod = '';
        if (count($args) > 0) {
            $smsMethod = ucfirst(array_shift($args));
        }

        if (count($args) == 0) {
            $args = null;
        }

        $className     = 'App\\Http\\SmsGateways\\Gateways\\' . $smsMethod;
        $this->gateway = new $className($args);
        return $this;
    }

    public function send($code, $phone, $message)
    {
        return $this->gateway->send($code, $phone, $message);
    }

    public function status()
    {
        return $this->gateway->status();
    }

}
