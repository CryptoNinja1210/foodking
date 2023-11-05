<?php

namespace App\Listeners;

use App\Events\SendSmsCode;
use App\Services\SmsManagerService;
use App\Services\SmsService;
use App\Sms\VerifyCode;
use Exception;
use Illuminate\Support\Facades\Log;

class SendSmsCodeNotification
{

    public SmsManagerService $smsManagerService;
    public string $gateway;

    public function __construct(SmsManagerService $smsManagerService, SmsService $smsService)
    {
        $this->smsManagerService = $smsManagerService;
        $this->gateway           = $smsService->gateway();
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\SendResetPassword $event
     * @return void
     */
    public function handle(SendSmsCode $event)
    {
        try {
            if ($this->smsManagerService->gateway($this->gateway)->status()) {
                $verifyCode = new VerifyCode($event->info['token']);
                $this->smsManagerService->gateway($this->gateway)->send(
                    $event->info['code'],
                    $event->info['phone'],
                    $verifyCode->build()
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
