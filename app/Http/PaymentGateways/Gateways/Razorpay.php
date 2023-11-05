<?php

namespace App\Http\PaymentGateways\Gateways;

use Exception;
use Razorpay\Api\Api as RazorPayClient;
use App\Enums\Activity;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Razorpay extends PaymentAbstract
{
    public mixed $response;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'razorpay'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            $this->gateway = new RazorPayClient($this->paymentGatewayOption['razorpay_key'], $this->paymentGatewayOption['razorpay_secret']);
        }
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse
    {
        $this->gateway->payment->fetch($request->razorpayPaymentId);
        if (!empty($request->razorpayPaymentId)) {
            try {
                $payment = $this->gateway->payment->fetch($request->razorpayPaymentId)->capture(['amount' => $order->total*100]);
                if($payment['status'] == 'captured') {
                    return redirect()->away(route('payment.success', [
                        'paymentGateway' => 'razorpay',
                        'order' => $order,
                        'token' => $request->razorpayPaymentId
                    ]));
                } else {
                    return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'razorpay'])->with(
                        'error',
                        trans('all.message.something_wrong')
                    );
                }
            } catch (Exception $e) {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'razorpay'])->with(
                    'error',
                    $e->getMessage()
                );
            }
        } else {
            return redirect()->route('payment.index', [
                'order' => $order,
                'paymentGateway' => 'razorpay'
            ])->with('error', trans('all.message.something_wrong'));
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'razorpay', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            if (isset($request->token)) {
                $paymentService = new PaymentService;
                $paymentService->payment($order, 'razorpay', $request->token);
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } else {
                return redirect()->route('payment.fail', [
                    'order' => $order,
                    'paymentGateway' => 'razorpay'
                ])->with('error', $this->response['message'] ?? trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', [
                'order' => $order,
                'paymentGateway' => 'razorpay'
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
