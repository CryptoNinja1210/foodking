<?php

namespace App\Http\SmsGateways\Gateways;


use App\Enums\Activity;
use App\Models\SmsGateway;
use App\Services\SmsAbstract;
use Exception;
use Illuminate\Support\Facades\Log;

class Msg91 extends SmsAbstract
{

    public string $msg91_key;
    public string $msg91_sender_id;


    public function __construct()
    {
        parent::__construct();

        $this->smsGateway = SmsGateway::with('gatewayOptions')->where(['slug' => 'msg91'])->first();
        if (!blank($this->smsGateway)) {
            $this->smsGatewayOption = $this->smsGateway->gatewayOptions->pluck('value', 'option');
            $this->msg91_key        = $this->smsGatewayOption['msg91_key'];
            $this->msg91_sender_id  = $this->smsGatewayOption['msg91_sender_id'];
        }
    }

    public function status(): bool
    {
        $paymentGateways = SmsGateway::where(['slug' => 'msg91', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function send($code, $phone, $message): void
    {
        try {

            $postData = array(
                'authkey' => $this->msg91_key,
                'mobiles' => trim($code, '+') . $phone,
                'message' => $message,
                'sender'  => $this->msg91_sender_id,
            );
            $url = "http://api.msg91.com/api/sendhttp.php";
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
            ));
            //Ignore SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_exec($ch);
            curl_close($ch);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }
}
