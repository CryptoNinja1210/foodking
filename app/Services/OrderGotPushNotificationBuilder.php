<?php

namespace App\Services;


use App\Enums\Role;
use App\Enums\SwitchBox;
use App\Models\FrontendOrder;
use App\Models\NotificationAlert;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderGotPushNotificationBuilder
{
    public int $orderId;
    public object $order;

    public function __construct($orderId,)
    {
        $this->orderId = $orderId;
        $this->order   = FrontendOrder::find($orderId);
    }

    public function send(): void
    {
        if (!blank($this->order)) {
            $fcmWebDeviceTokenAllAdmins         = User::role(Role::ADMIN)->where(['branch_id' => 0])->whereNotNull('web_token')->get();
            $fcmWebDeviceTokenBranchAdmins      = User::role(Role::ADMIN)->where(['branch_id' => $this->order->branch_id])->whereNotNull('web_token')->get();
            $fcmWebDeviceTokenBranchManagers    = User::role(Role::BRANCH_MANAGER)->where(['branch_id' => $this->order->branch_id])->whereNotNull('web_token')->get();
            $fcmMobileDeviceTokenAllAdmins      = User::role(Role::ADMIN)->where(['branch_id' => 0])->whereNotNull('device_token')->get();
            $fcmMobileDeviceTokenBranchAdmins   = User::role(Role::ADMIN)->where(['branch_id' => $this->order->branch_id])->whereNotNull('device_token')->get();
            $fcmMobileDeviceTokenBranchManagers = User::role(Role::BRANCH_MANAGER)->where(['branch_id' => $this->order->branch_id])->whereNotNull('device_token')->get();

            $i             = 0;
            $fcmTokenArray = [];
            if (!blank($fcmWebDeviceTokenAllAdmins)) {
                foreach ($fcmWebDeviceTokenAllAdmins as $fcmWebDeviceTokenAllAdmin) {
                    $fcmTokenArray[$i] = $fcmWebDeviceTokenAllAdmin->web_token;
                    $i++;
                }
            }

            if (!blank($fcmWebDeviceTokenBranchAdmins)) {
                foreach ($fcmWebDeviceTokenBranchAdmins as $fcmWebDeviceTokenBranchAdmin) {
                    $fcmTokenArray[$i] = $fcmWebDeviceTokenBranchAdmin->web_token;
                    $i++;
                }
            }

            if (!blank($fcmWebDeviceTokenBranchManagers)) {
                foreach ($fcmWebDeviceTokenBranchManagers as $fcmWebDeviceTokenBranchManager) {
                    $fcmTokenArray[$i] = $fcmWebDeviceTokenBranchManager->web_token;
                    $i++;
                }
            }

            if (!blank($fcmMobileDeviceTokenAllAdmins)) {
                foreach ($fcmMobileDeviceTokenAllAdmins as $fcmMobileDeviceTokenAllAdmin) {
                    $fcmTokenArray[$i] = $fcmMobileDeviceTokenAllAdmin->device_token;
                    $i++;
                }
            }

            if (!blank($fcmMobileDeviceTokenBranchAdmins)) {
                foreach ($fcmMobileDeviceTokenBranchAdmins as $fcmMobileDeviceTokenBranchAdmin) {
                    $fcmTokenArray[$i] = $fcmMobileDeviceTokenBranchAdmin->device_token;
                    $i++;
                }
            }

            if (!blank($fcmMobileDeviceTokenBranchManagers)) {
                foreach ($fcmMobileDeviceTokenBranchManagers as $fcmMobileDeviceTokenBranchManager) {
                    $fcmTokenArray[$i] = $fcmMobileDeviceTokenBranchManager->device_token;
                    $i++;
                }
            }

            if (count($fcmTokenArray) > 0) {
                try {
                    $notificationAlert = NotificationAlert::where(['language' => 'admin_and_branch_manager_new_order_message'])->first();
                    if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
                        $pushNotification = (object)[
                            'title'       => 'New Order Notification',
                            'description' => $notificationAlert->push_notification_message,
                            'order_id'    => $this->orderId
                        ];
                        $firebase         = new FirebaseService();
                        $firebase->sendNotification($pushNotification, $fcmTokenArray, "new-order-found");
                    }
                } catch (Exception $e) {
                    Log::info($e->getMessage());
                }
            }

        }
    }
}
