<?php

namespace App\Http\PaymentGateways\Gateways;


use App\Enums\Activity;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Services\PaymentAbstract;
use Exception;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Paypal extends PaymentAbstract
{
    public mixed $response;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'paypal'])->first();
        if (!blank($this->paymentGateway)) {
            $currencyCode = 'USD';
            $currencyId   = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }

            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            $config                     = [
                "mode"           => $this->paymentGatewayOption['paypal_mode'] == '5' ? 'sandbox' : 'live',
                'sandbox'        => [
                    'client_id'     => $this->paymentGatewayOption['paypal_client_id'],
                    'client_secret' => $this->paymentGatewayOption['paypal_client_secret'],
                    'app_id'        => $this->paymentGatewayOption['paypal_app_id'],
                ],
                'live'           => [
                    'client_id'     => $this->paymentGatewayOption['paypal_client_id'],
                    'client_secret' => $this->paymentGatewayOption['paypal_client_secret'],
                    'app_id'        => $this->paymentGatewayOption['paypal_app_id'],
                ],
                "payment_action" => "Sale",
                "currency"       => $currencyCode,
                "notify_url"     => "",
                "locale"         => "en_US",
                "validate_ssl"   => true
            ];
            $this->gateway              = new PayPalClient($config);
        }
    }

    public function payment($order, $request) : \Illuminate\Http\RedirectResponse
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

            $this->gateway->getAccessToken();
            $response = $this->gateway->createOrder([
                "intent"              => "CAPTURE",
                "application_context" => [
                    "return_url" => route('payment.success', ['order' => $order, 'paymentGateway' => 'paypal']),
                    "cancel_url" => route('payment.cancel', ['order' => $order, 'paymentGateway' => 'paypal']),
                ],
                "purchase_units"      => [
                    0 => [
                        "amount" => [
                            "currency_code" => $currencyCode,
                            "value"         => (float)$order->total
                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'paypal'])->with(
                    'error',
                    trans('all.message.something_wrong')
                );
            } else {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'paypal'])->with(
                    'error',
                    $response['message'] ?? trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'paypal'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status() : bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'paypal', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request) : \Illuminate\Http\RedirectResponse
    {
        try {
            DB::transaction(function () use ($order, $request) {
                $this->gateway->getAccessToken();
                $this->response = $this->gateway->capturePaymentOrder($request['token']);
            });

            if (isset($this->response['status']) && $this->response['status'] == 'COMPLETED') {
                $this->paymentService->payment($order, 'paypal', $this->response['id']);
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            } else {
                return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'paypal'])->with(
                    'error',
                    $this->response['message'] ?? trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'paypal'])->with(
                'error',
               $e->getMessage()
            );
        }
    }

    public function fail($order, $request) : \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order])->with(
            'error',
            trans('all.message.something_wrong')
        );
    }

    public function cancel($order, $request) : \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }
}
