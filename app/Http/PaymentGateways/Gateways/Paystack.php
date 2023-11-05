<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use App\Enums\Activity;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Smartisan\Settings\Facades\Settings;
use Unicodeveloper\Paystack\Facades\Paystack as PaystackClient;

class Paystack extends PaymentAbstract
{

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'paystack'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            Config::set([
                'paystack' => [
                    'publicKey'  => $this->paymentGatewayOption['paystack_public_key'],
                    'secretKey'  => $this->paymentGatewayOption['paystack_secret_key'],
                    'paymentUrl' => $this->paymentGatewayOption['paystack_payment_url'],
                ]
            ]);
        }
    }

    public function payment($order, $request)
    {
        try {
            $currencyCode = 'NGN';
            $currencyId   = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }
            $data = [
                "amount"       => $order->total * 100,
                "reference"    => PaystackClient::genTranxRef(),
                "email"        => $order->user?->email,
                "currency"     => $currencyCode,
                "orderID"      => $order->order_serial_no,
                "callback_url" => route('payment.success', ['order' => $order, 'paymentGateway' => 'paystack']),
            ];
            return PaystackClient::getAuthorizationUrl($data)->redirectNow();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', [
                'order'          => $order,
                'paymentGateway' => 'paystack'
            ])->with('error', $e->getMessage());
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'paystack', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $response = PaystackClient::getPaymentData();
            if (isset($response['data']['status']) && $response['data']['status'] == 'success') {
                $this->paymentService->payment($order, 'paystack', $response['data']['reference']);
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } else {
                return redirect()->route('payment.fail', [
                    'order'          => $order,
                    'paymentGateway' => 'paystack'
                ])->with('error', trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'paystack'
            ])->with('error', $e->getMessage());
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order])->with('error', trans('all.message.something_wrong'));
    }

    public function cancel($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }
}
