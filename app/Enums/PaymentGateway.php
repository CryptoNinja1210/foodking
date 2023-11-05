<?php
namespace App\Enums;

interface PaymentGateway
{
    const CASH_ON_DELIVERY = 1;
    const E_WALLET         = 2;
    const PAYPAL           = 3;
}
