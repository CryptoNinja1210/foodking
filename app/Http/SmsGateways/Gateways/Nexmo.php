<?php

namespace App\Http\SmsGateways\Gateways;


use Exception;
use Vonage\Client;
use App\Enums\Activity;
use App\Models\SmsGateway;
use Vonage\SMS\Message\SMS;
use App\Services\SmsAbstract;
use Illuminate\Support\Facades\Log;
use Vonage\Client\Credentials\Basic;


class Nexmo extends SmsAbstract
{
    public object $basic;

    public function __construct()
    {
        parent::__construct();
        $this->smsGateway = SmsGateway::with('gatewayOptions')->where(['slug' => 'nexmo'])->first();
        if (!blank($this->smsGateway)) {
            $this->smsGatewayOption = $this->smsGateway->gatewayOptions->pluck('value', 'option');
            $this->basic    = new Basic($this->smsGatewayOption['nexmo_key'], $this->smsGatewayOption['nexmo_secret']);
            $this->gateway  = new Client($this->basic);
        }
    }

    public function status(): bool
    {
        $paymentGateways = SmsGateway::where(['slug' => 'nexmo', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function send($code, $phone, $message): void
    {
        try {
            $this->gateway->sms()->send(new SMS($code . $phone, env('APP_NAME'), $message));
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }
}
