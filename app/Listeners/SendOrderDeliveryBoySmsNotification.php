<?php

namespace App\Listeners;

use App\Events\SendOrderDeliveryBoySms;
use App\Services\OrderDeliveryBoySmsNotificationBuilder;
use Illuminate\Support\Facades\Log;

class SendOrderDeliveryBoySmsNotification
{
    public function handle(SendOrderDeliveryBoySms $event)
    {
        try{
            $orderDeliveryBoySmsNotificationBuilderService = new OrderDeliveryBoySmsNotificationBuilder($event->info['order_id'], $event->info['status']);
            $orderDeliveryBoySmsNotificationBuilderService->send();
        } catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
