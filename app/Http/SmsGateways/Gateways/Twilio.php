<?php

namespace App\Http\SmsGateways\Gateways;


use App\Enums\Activity;
use App\Models\SmsGateway;
use App\Services\SmsAbstract;
use Exception;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client as TwilioClient;

class Twilio extends SmsAbstract
{

    public $from;

    /**
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();

        $this->smsGateway = SmsGateway::with('gatewayOptions')->where(['slug' => 'twilio'])->first();
        if (!blank($this->smsGateway)) {
            $this->smsGatewayOption = $this->smsGateway->gatewayOptions->pluck('value', 'option');
            $this->from             = $this->smsGatewayOption['twilio_from'];
            $this->smsGateway       = new TwilioClient(
                $this->smsGatewayOption['twilio_account_sid'],
                $this->smsGatewayOption['twilio_auth_token']
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = SmsGateway::where(['slug' => 'twilio', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function send($code, $phone, $message)
    {
        try {
            $this->smsGateway->messages->create($code . $phone, [
                'from' => $this->from,
                'body' => $message
            ]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }
}