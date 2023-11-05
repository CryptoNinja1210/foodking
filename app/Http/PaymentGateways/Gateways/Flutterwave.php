<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use App\Enums\Activity;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use KingFlamez\Rave\Facades\Rave;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Smartisan\Settings\Facades\Settings;

class Flutterwave extends PaymentAbstract
{
    protected mixed $publicKey;
    protected mixed $secretKey;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'flutterwave'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            $this->publicKey            = $this->paymentGatewayOption['flutterwave_public_key'];
            $this->secretKey            = $this->paymentGatewayOption['flutterwave_secret_key'];
            Config::set('flutterwave.publicKey', $this->publicKey);
            Config::set('flutterwave.secretKey', $this->secretKey);
        }
    }

    public function payment($order, $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $currencyCode = 'USD';
            $currencyId   = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }

            $reference = Rave::generateReference();

            $data = [
                'payment_options' => 'card',
                'amount'          => $order->total,
                'email'           => $order->user?->email,
                'tx_ref'          => $reference,
                'currency'        => $currencyCode,
                'redirect_url'    => route('payment.success', ['order' => $order, 'paymentGateway' => 'flutterwave']),
                'customer'        => [
                    'email'        => $order->user?->email,
                    "phone_number" => $order->user?->phone,
                    "name"         => $order->user?->name
                ],
                "customizations" => [
                    "title"       => 'Buy Food',
                    "description" => "20th October"
                ]
            ];

            $payment = Rave::initializePayment($data);

            if ($payment['status'] == 'success') {
                return redirect($payment['data']['link']);
            } else {
                return redirect()->route('payment.index', [
                    'order'          => $order,
                    'paymentGateway' => 'flutterwave'
                ])->with('error', trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', [
                'order'          => $order,
                'paymentGateway' => 'flutterwave'
            ])->with('error', $e->getMessage());
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'flutterwave', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {

            if (isset($request['status']) && $request['status'] == 'successful') {
                $this->paymentService->payment($order, 'flutterwave', $request['tx_ref']);
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } else {
                return redirect()->route('payment.fail', [
                    'order'          => $order,
                    'paymentGateway' => 'flutterwave'
                ])->with('error', trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'flutterwave'
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
