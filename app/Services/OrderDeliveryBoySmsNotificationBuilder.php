<?php

namespace App\Services;


use App\Enums\SwitchBox;
use App\Models\FrontendOrder;
use App\Models\NotificationAlert;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderDeliveryBoySmsNotificationBuilder
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
            if ($this->order->delivery_boy_id > 0) {
                $deliveryBoy = User::find($this->order->delivery_boy_id);
                if (!blank($deliveryBoy)) {
                    if ($deliveryBoy->phone) {
                        $this->message(
                            $deliveryBoy->name,
                            $deliveryBoy->country_code,
                            $deliveryBoy->phone,
                            $this->status,
                            $this->order->order_serial_no
                        );
                    }
                }
            }
        }
    }

    private function message($name, $code, $phone, $status, $orderId)
    {
        if ($status == 101) {
            $this->deliveryBoyAssign($name, $code, $phone, $orderId);
        }
    }

    private function sms($name, $code, $phone, $orderId, $message)
    {
        try {
            $smsManagerService = new SmsManagerService();
            $smsService        = new SmsService();
            if ($smsService->gateway() && $smsManagerService->gateway($smsService->gateway())->status()) {
                $smsManagerService->gateway($smsService->gateway())->send($code, $phone, $message);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function deliveryBoyAssign($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'delivery_boy_after_assign_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }
}
