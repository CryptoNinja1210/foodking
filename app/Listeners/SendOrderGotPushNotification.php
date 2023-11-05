<?php

namespace App\Listeners;


use App\Events\SendOrderGotPush;
use App\Services\OrderGotPushNotificationBuilder;
use Illuminate\Support\Facades\Log;

class SendOrderGotPushNotification
{
    public function handle(SendOrderGotPush $event)
    {
        try{
            $orderPushNotificationBuilderService = new OrderGotPushNotificationBuilder($event->info['order_id']);
            $orderPushNotificationBuilderService->send();
        } catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
