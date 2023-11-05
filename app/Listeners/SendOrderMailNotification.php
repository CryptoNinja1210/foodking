<?php

namespace App\Listeners;

use App\Events\SendOrderMail;
use App\Services\OrderMailNotificationBuilder;
use Illuminate\Support\Facades\Log;

class SendOrderMailNotification
{
    public function handle(SendOrderMail $event)
    {
        try{
            $orderMailNotificationBuilderService = new OrderMailNotificationBuilder($event->info['order_id'], $event->info['status']);
            $orderMailNotificationBuilderService->send();
        } catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
