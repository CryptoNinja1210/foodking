<?php

namespace App\Services;


use App\Enums\SwitchBox;
use App\Mail\OrderMail;
use App\Models\FrontendOrder;
use App\Models\NotificationAlert;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderDeliveryBoyMailNotificationBuilder
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
                    if ($deliveryBoy->email) {
                        $this->message($deliveryBoy->name, $deliveryBoy->email, $this->status, $this->order->order_serial_no);
                    }
                }
            }
        }
    }

    private function message($name, $email, $status, $orderId)
    {
        if ($status == 101) {
            $this->deliveryBoyAssign($name, $email, $orderId);
        }
    }

    private function mail($name, $email, $orderId, $message)
    {
        try {
            Mail::to($email)->send(new OrderMail($name, $orderId, $message));
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function deliveryBoyAssign($name, $email, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'delivery_boy_after_assign_message'])->first();
        if ($notificationAlert && $notificationAlert->mail == SwitchBox::ON) {
            $this->mail($name, $email, $orderId, $notificationAlert->mail_message);
        }
    }
}
