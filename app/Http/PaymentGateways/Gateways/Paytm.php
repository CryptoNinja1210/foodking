<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use App\Enums\Activity;
use App\Enums\GatewayMode;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;

class Paytm extends PaymentAbstract
{
    public mixed $response;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'paytm'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            config([
                'services.paytm-wallet' => [
                    'env' => $this->paymentGatewayOption['paytm_mode'] == GatewayMode::SANDBOX ? 'locale' : 'production',
                    'merchant_id' => $this->paymentGatewayOption['paytm_merchant_id'],
                    'merchant_key' => $this->paymentGatewayOption['paytm_merchant_key'],
                    'merchant_website' => $this->paymentGatewayOption['paytm_merchant_website'],
                    'channel' => $this->paymentGatewayOption['paytm_channel'],
                    'industry_type' => $this->paymentGatewayOption['paytm_industry_type'],
                ]
            ]);
        }
    }

    public function payment($order, $request)
    {
        try {
            $data = PaytmWallet::with('receive');
            $data->prepare([
                'order' => $order->order_serial_no,
                'user' => $order->user?->id,
                'mobile_number' => $order->user?->phone,
                'email' => $order->user?->email,
                'amount' => number_format((float)$order->total, 2, '.', ''),
                'callback_url' => route('payment.success', ['order' => $order, 'paymentGateway' => 'paytm'])
            ]);

            return $data->receive();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order,
                'paymentGateway' => 'paytm'
            ])->with('error', $e->getMessage());
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'paytm', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            if (isset($request['TXNID']) && $request['STATUS'] == 'TXN_SUCCESS') {
                $this->paymentService->payment($order, 'paytm', $request['TXNID']);
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } else {
                return redirect()->route('payment.fail', ['order' => $order,
                    'paymentGateway' => 'paytm'
                ])->with('error', $request['RESPMSG'] ?? trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', [
                'order' => $order,
                'paymentGateway' => 'paytm'
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
