<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use App\Enums\Activity;
use App\Models\Currency;
use App\Enums\GatewayMode;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Smartisan\Settings\Facades\Settings;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class Bkash extends PaymentAbstract
{
    public mixed $response;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'bkash'])->first();
        $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
        Config::set('bkash.sandbox', $this->paymentGatewayOption['bkash_mode'] == GatewayMode::SANDBOX ? true : false);
        Config::set('bkash.bkash_app_key', $this->paymentGatewayOption['bkash_app_key']);
        Config::set('bkash.bkash_app_secret', $this->paymentGatewayOption['bkash_app_secret']);
        Config::set('bkash.bkash_username', $this->paymentGatewayOption['bkash_username']);
        Config::set('bkash.bkash_password', $this->paymentGatewayOption['bkash_password']);
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $currencyCode = 'BDT';
            $currencyId   = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }

            Config::set('bkash.callbackURL', route('payment.success', ['order' => $order, 'paymentGateway' => 'bkash']));

            $request['intent']                = 'sale';
            $request['mode']                  = '0011';
            $request['payerReference']        = $order->order_serial_no;
            $request['currency']              = $currencyCode;
            $request['amount']                = (int)number_format($order->total);
            $request['merchantInvoiceNumber'] = $order->order_serial_no;
            $request['callbackURL'] = route('payment.success', ['order' => $order, 'paymentGateway' => 'bkash']);

            $dataJson = json_encode($request->all());
            $bkash =  BkashPaymentTokenize::cPayment($dataJson);

            if (isset($bkash['bkashURL'])) {
                return redirect()->away($bkash['bkashURL']);
            } else {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'bkash'])->with(
                    'error',
                    $bkash['statusMessage']
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'bkash'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'bkash', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {

            if ($request['statusCode'] == "0000" && $request['statusMessage'] == 'Successful') {
                $this->paymentService->payment($order, 'bkash', $request['paymentID']);
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            } else {
                return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'bkash'])->with(
                    'error',
                    $request['status'] ?? trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'paypal'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order])->with(
            'error',
            trans('all.message.something_wrong')
        );
    }

    public function cancel($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }
}
