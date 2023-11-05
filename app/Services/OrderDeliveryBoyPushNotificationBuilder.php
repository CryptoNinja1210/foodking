<?php

namespace App\Services;


use App\Enums\OrderStatus;
use App\Enums\SwitchBox;
use App\Models\FrontendOrder;
use App\Models\NotificationAlert;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderDeliveryBoyPushNotificationBuilder
{
    public int $orderId;
    public mixed $status;
    public object $order;

    public function __construct($orderId, $status)
    {
        $this->orderId = $orderId;
        $this->status  = $status;
        $this->order   = FrontendOrder::find($orderId);
    }

    public function send()
    {
        if (!blank($this->order)) {
            if($this->order->delivery_boy_id > 0) {
                $deliveryBoy = User::find($this->order->delivery_boy_id);
                if (!blank($deliveryBoy)) {
                    if (!blank($deliveryBoy->web_token) || !blank($deliveryBoy->device_token)) {
                        $fcmTokenArray = [];
                        if (!blank($deliveryBoy->web_token)) {
                            $fcmTokenArray[] = $deliveryBoy->web_token;
                        }
                        if (!blank($deliveryBoy->device_token)) {
                            $fcmTokenArray[] = $deliveryBoy->device_token;
                        }
                        $this->message($fcmTokenArray, $this->status, $this->orderId);
                    }
                }
            }

        }
    }

    private function message($fcmTokenArray, $status, $orderId)
    {
        if ($status == 101) {
            $this->deliveryBoyAssign($fcmTokenArray, $orderId);
        }
    }

    private function notification($fcmTokenArray, $orderId, $message)
    {
        try {
            $pushNotification = (object)[
                'title'       => 'Order Notification',
                'description' => $message,
                'order_id'    => $orderId
            ];
            $firebase         = new FirebaseService();
            $firebase->sendNotification($pushNotification, $fcmTokenArray, "Order Notification");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function deliveryBoyAssign($fcmTokenArray, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'delivery_boy_after_assign_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }
}
