<?php

namespace App\Listeners;

use App\Events\SendOrderDeliveryBoyMail;
use App\Services\OrderDeliveryBoyMailNotificationBuilder;
use Illuminate\Support\Facades\Log;

class SendOrderDeliveryBoyMailNotification
{
    public function handle(SendOrderDeliveryBoyMail $event)
    {
        try {
            $orderDeliveryBoyMailNotificationBuilderService = new OrderDeliveryBoyMailNotificationBuilder(
                $event->info['order_id'],
                $event->info['status']
            );
            $orderDeliveryBoyMailNotificationBuilderService->send();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
