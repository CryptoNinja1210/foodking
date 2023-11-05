<?php

namespace App\Services;

abstract class SmsAbstract
{

    public object $gateway;
    public object $smsGateway;
    public object $smsGatewayOption;

    public function __construct()
    {
    }

    abstract public function status();

    abstract public function send($code, $phone, $message);
}
