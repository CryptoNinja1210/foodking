<?php

namespace App\Http\PaymentGateways\Gateways;


use App\Models\CapturePaymentNotification;
use Exception;
use App\Enums\Activity;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;
use Mollie\Laravel\Facades\Mollie as MollieClient;

class Mollie extends PaymentAbstract
{
    public bool $response = false;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'mollie'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            MollieClient::api()->setApiKey($this->paymentGatewayOption['mollie_api_key']);
        }
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse
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

            $payment = MollieClient::api()->payments->create([
                "amount"      => [
                    "currency" => $currencyCode,
                    "value"    => number_format((float)$order->total, 2, '.', '')
                ],
                "description" => $order->order_serial_no,
                "redirectUrl" => route('payment.success', ['order' => $order, 'paymentGateway' => 'mollie']),
                "cancelUrl"   => route('payment.cancel', ['order' => $order, 'paymentGateway' => 'mollie'])
            ]);

            if (isset($payment->id) && $payment->id) {
                $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                    ['order_id', $order->id]
                ]);

                $capturePaymentNotification?->delete();
                $token = $payment->id;
                CapturePaymentNotification::create([
                    'order_id'   => $order->id,
                    'token'      => $token,
                    'created_at' => now()
                ]);
                $payment = MollieClient::api()->payments()->get($token);
                return redirect()->away($payment->getCheckoutUrl());
            } else {
                return redirect()->route('payment.index', [
                    'order'          => $order,
                    'paymentGateway' => 'mollie'
                ])->with('error', trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', [
                'order'          => $order,
                'paymentGateway' => 'mollie'
            ])->with('error', $e->getMessage());
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'mollie', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                ['order_id', $order->id]
            ]);
            $token                      = $capturePaymentNotification->first();
            if (!blank($token) && $order->id == $token->order_id) {
                $response = MollieClient::api()->payments()->get($token->token);
                if ($response->isPaid()) {
                    DB::transaction(function() use ($order, $request, $token, $capturePaymentNotification) {
                        $this->paymentService->payment($order, 'mollie', $token->token);
                        $capturePaymentNotification->delete();
                        $this->response = true;
                    });
                }
            }

            if ($this->response) {
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            }
            return redirect()->route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'mollie'
            ])->with('error', trans('all.message.something_wrong'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'mollie'
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
