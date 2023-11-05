<?php

namespace App\Sms;


class VerifyCode
{

    public Mixed $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return "Your code is " . $this->code;
    }
}
