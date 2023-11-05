<?php

namespace App\Listeners;

use App\Events\SendResetPassword;
use App\Mail\ResetPassword;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\SendResetPassword $event
     * @return void
     */
    public function handle(SendResetPassword $event)
    {
        try {
            Mail::to($event->info['email'])->send(new ResetPassword($event->info['pin']));
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
