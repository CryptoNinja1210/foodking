<?php

namespace App\Listeners;


use App\Events\SendOrderPush;
use App\Services\OrderPushNotificationBuilder;
use Illuminate\Support\Facades\Log;

class SendOrderPushNotification
{
    public function handle(SendOrderPush $event)
    {
        try{
            $orderPushNotificationBuilderService = new OrderPushNotificationBuilder($event->info['order_id'], $event->info['status']);
            $orderPushNotificationBuilderService->send();
        } catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
