<?php

namespace App\Http\SmsGateways\Gateways;


use Exception;
use GuzzleHttp\Client;
use App\Enums\Activity;
use App\Models\SmsGateway;
use App\Services\SmsAbstract;
use Illuminate\Support\Facades\Log;

class Clickatell extends SmsAbstract
{

    public string $apiKey;
    public string $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->smsGateway = SmsGateway::with('gatewayOptions')->where(['slug' => 'clickatell'])->first();
        if (!blank($this->smsGateway)) {
            $this->smsGatewayOption = $this->smsGateway->gatewayOptions->pluck('value', 'option');
            $this->gateway = new Client();
            $this->baseUrl = 'https://api.clickatell.com/http/sendmsg';
            $this->apiKey = $this->smsGatewayOption['clickatell_apikey'];

        }
    }

    public function status(): bool
    {
        $paymentGateways = SmsGateway::where(['slug' => 'clickatell', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function send($code, $phone, $message): void
    {
        try {
            $params = [
                'api_id' => $this->apiKey,
                'to' => $code . $phone,
                'text' => $message,
            ];

            $this->gateway->get($this->baseUrl, ['query' => $params]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }
}
