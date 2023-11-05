<?php

namespace App\Services;


use App\Enums\OrderStatus;
use App\Enums\SwitchBox;
use App\Models\FrontendOrder;
use App\Models\NotificationAlert;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderSmsNotificationBuilder
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
            $user = User::find($this->order->user_id);
            if (!blank($user)) {
                if ($user->phone) {
                    $this->message(
                        $user->name,
                        $user->country_code,
                        $user->phone,
                        $this->status,
                        $this->order->order_serial_no
                    );
                }
            }
        }
    }

    private function message($name, $code, $phone, $status, $orderId)
    {
        if ($status == OrderStatus::PENDING) {
            $this->pending($name, $code, $phone, $orderId);
        } elseif ($status == OrderStatus::ACCEPT) {
            $this->confirmation($name, $code, $phone, $orderId);
        } elseif ($status == OrderStatus::PROCESSING) {
            $this->processing($name, $code, $phone, $orderId);
        } elseif ($status == OrderStatus::OUT_FOR_DELIVERY) {
            $this->outForDelivery($name, $code, $phone, $orderId);
        } elseif ($status == OrderStatus::DELIVERED) {
            $this->delivered($name, $code, $phone, $orderId);
        } elseif ($status == OrderStatus::CANCELED) {
            $this->canceled($name, $code, $phone, $orderId);
        } elseif ($status == OrderStatus::REJECTED) {
            $this->rejected($name, $code, $phone, $orderId);
        } elseif ($status == OrderStatus::RETURNED) {
            $this->returned($name, $code, $phone, $orderId);
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

    private function pending($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_pending_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }

    private function confirmation($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_confirmation_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }

    private function processing($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_processing_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }

    private function outForDelivery($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_out_for_delivery_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }

    private function delivered($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_delivered_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }

    private function canceled($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_canceled_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }

    private function rejected($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_rejected_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }

    private function returned($name, $code, $phone, $orderId)
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_returned_message'])->first();
        if ($notificationAlert && $notificationAlert->sms == SwitchBox::ON) {
            $this->sms($name, $code, $phone, $orderId, $notificationAlert->sms_message);
        }
    }
}