<?php

namespace App\Providers;

use App\Events\SendOrderDeliveryBoyMail;
use App\Events\SendOrderDeliveryBoyPush;
use App\Events\SendOrderDeliveryBoySms;
use App\Events\SendOrderGotPush;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Events\SendOrderSms;
use App\Events\SendResetPassword;
use App\Events\SendSmsCode;
use App\Listeners\SendOrderDeliveryBoyMailNotification;
use App\Listeners\SendOrderDeliveryBoyPushNotification;
use App\Listeners\SendOrderDeliveryBoySmsNotification;
use App\Listeners\SendOrderGotPushNotification;
use App\Listeners\SendOrderMailNotification;
use App\Listeners\SendOrderPushNotification;
use App\Listeners\SendOrderSmsNotification;
use App\Listeners\SendResetPasswordNotification;
use App\Listeners\SendSmsCodeNotification;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class               => [
            SendEmailVerificationNotification::class
        ],
        SendResetPassword::class        => [
            SendResetPasswordNotification::class
        ],
        SendSmsCode::class              => [
            SendSmsCodeNotification::class
        ],
        SendOrderMail::class            => [
            SendOrderMailNotification::class
        ],
        SendOrderSms::class             => [
            SendOrderSmsNotification::class
        ],
        SendOrderPush::class            => [
            SendOrderPushNotification::class
        ],
        SendOrderDeliveryBoyMail::class => [
            SendOrderDeliveryBoyMailNotification::class
        ],
        SendOrderDeliveryBoySms::class  => [
            SendOrderDeliveryBoySmsNotification::class
        ],
        SendOrderDeliveryBoyPush::class => [
            SendOrderDeliveryBoyPushNotification::class
        ],
        SendOrderGotPush::class         => [
            SendOrderGotPushNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
