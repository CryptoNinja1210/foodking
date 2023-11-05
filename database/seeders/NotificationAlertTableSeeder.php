<?php

namespace Database\Seeders;

use App\Enums\SwitchBox;
use Illuminate\Database\Seeder;
use App\Models\NotificationAlert;

class NotificationAlertTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public array $notificationAlerts = [
        'name'    => [
            'Order Pending Message',
            'Order Confirmation Message',
            'Order Processing Message',
            'Order Out For Delivery Message',
            'Order Delivered Message',
            'Order Canceled Message',
            'Order Rejected Message',
            'Order Returned Message',
            'Delivery Boy After Assign Message',
            'Admin And Branch Manager New Order Message',
        ],
        'message' => [
            'Your order is successfully placed.',
            'Your order is Confirmed.',
            'Your order is being Processed.',
            'Your order is Out for delivery.',
            'Your order is Successfully delivered.',
            'Your order is Canceled.',
            'Your order is Rejected.',
            'Your order is Returned.',
            'You have been assigned an order for delivery.',
            'You have a new order.',
        ]

    ];

    public function run()
    {
        foreach ($this->notificationAlerts['name'] as $key => $notificationAlert) {
            NotificationAlert::create([
                'name'                      => $notificationAlert,
                'language'                  => str_replace(' ', '_', strtolower($notificationAlert)),
                'mail_message'              => $this->notificationAlerts['message'][$key],
                'sms_message'               => $this->notificationAlerts['message'][$key],
                'push_notification_message' => $this->notificationAlerts['message'][$key],
                'mail'                      => SwitchBox::OFF,
                'sms'                       => SwitchBox::OFF,
                'push_notification'         => SwitchBox::OFF,
            ]);
        }
    }
}
