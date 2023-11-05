<?php

namespace App\Listeners;


use App\Events\SendOrderDeliveryBoyPush;
use App\Services\OrderDeliveryBoyPushNotificationBuilder;
use Illuminate\Support\Facades\Log;

class SendOrderDeliveryBoyPushNotification
{
    public function handle(SendOrderDeliveryBoyPush $event)
    {
        try {
            $orderDeliveryBoyPushNotificationBuilderService = new OrderDeliveryBoyPushNotificationBuilder(
                $event->info['order_id'], $event->info['status']
            );
            $orderDeliveryBoyPushNotificationBuilderService->send();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
