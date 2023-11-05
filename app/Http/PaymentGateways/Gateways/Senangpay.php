<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use App\Models\Order;
use App\Enums\Activity;
use App\Enums\GatewayMode;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Senangpay extends PaymentAbstract
{
    protected mixed $merchantId;
    protected mixed $secretKey;
    protected mixed $baseUrl;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'senangpay'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            $this->merchantId           = $this->paymentGatewayOption['senangpay_merchant_id'];
            $this->secretKey            = $this->paymentGatewayOption['senangpay_secret_key'];
            $this->baseUrl              = $this->paymentGatewayOption['senangpay_mode'] == GatewayMode::SANDBOX ? ' https://sandbox.senangpay.my/payment/' : 'https://app.senangpay.my/payment/';
        }
    }

    public function payment($order, $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $detail  = "Order For Food";
            $amount  = number_format((float)$order->total, 2, '.', '');
            $hashKey = md5($this->secretKey . $detail . $amount . $order->order_serial_no);

            $data = http_build_query([
                'detail'   => $detail,
                'amount'   => $amount,
                'order_id' => $order->order_serial_no,
                'hash'     => $hashKey,
                'name'     => $order->user?->name,
                'email'    => $order->user?->email,
                'phone'    => $order->user?->phone,
            ]);

            if (!blank($this->merchantId) && !blank($this->secretKey) && !blank($this->baseUrl)) {
                return redirect()->away($this->baseUrl . $this->merchantId . '?' . $data);
            } else {
                return redirect()->route('payment.index', [
                    'order'          => $order,
                    'paymentGateway' => 'senangpay'
                ])->with('error', trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', [
                'order'          => $order,
                'paymentGateway' => 'senangpay'
            ])->with('error', $e->getMessage());
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'senangpay', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            if (isset($request['status_id']) && $request['status_id'] == '1') {
                $this->paymentService->payment($order, 'senangpay', $request['transaction_id']);
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } else {
                return redirect()->route('payment.fail', [
                    'order'          => $order,
                    'paymentGateway' => 'senangpay'
                ])->with('error', $request['msg']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'senangpay'
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

    public function webhook(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $order = Order::where('order_serial_no', $request['order_id'])->first();
            return $this->success($order, $request);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'senangpay'
            ])->with('error', $e->getMessage());
        }
    }
}
