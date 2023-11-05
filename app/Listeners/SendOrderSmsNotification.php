<?php

namespace App\Listeners;

use App\Events\SendOrderSms;
use App\Services\OrderSmsNotificationBuilder;
use Illuminate\Support\Facades\Log;

class SendOrderSmsNotification
{
    public function handle(SendOrderSms $event)
    {
        try{
            $orderSmsNotificationBuilderService = new OrderSmsNotificationBuilder($event->info['order_id'], $event->info['status']);
            $orderSmsNotificationBuilderService->send();
        } catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
