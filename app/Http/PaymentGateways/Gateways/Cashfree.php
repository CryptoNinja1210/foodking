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
use Illuminate\Support\Facades\Config;
use LoveyCom\CashFree\PaymentGateway\Order as CashfreeClient;

class Cashfree extends PaymentAbstract
{

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'cashfree'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            Config::set('cashfree.appID', $this->paymentGatewayOption['cashfree_app_id']);
            Config::set('cashfree.secretKey', $this->paymentGatewayOption['cashfree_secret_key']);
            Config::set('cashfree.PG.appID', $this->paymentGatewayOption['cashfree_app_id']);
            Config::set('cashfree.PG.secretKey', $this->paymentGatewayOption['cashfree_secret_key']);
            Config::set('cashfree.PG.isLive', !($this->paymentGatewayOption['cashfree_mode'] == GatewayMode::SANDBOX));
        }
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $cashfree = new CashfreeClient();
            $data["orderId"] = $order->order_serial_no;
            $data["orderAmount"] = floatval($order->total);
            $data["orderNote"] = "";
            $data["customerPhone"] = $order->user?->phone;
            $data["customerName"] = $order->user?->name;
            $data["customerEmail"] = $order->user?->email;
            $data["returnUrl"] = route('payment.success', ['order' => $order, 'paymentGateway' => 'cashfree']);
            $data["notifyUrl"] = route('payment.success', ['order' => $order, 'paymentGateway' => 'cashfree']);
            $cashfree->create($data);
            $link = $cashfree->getLink($data['orderId']);

            if (isset($link->paymentLink) && $link->status == "OK") {
                return redirect()->away($link->paymentLink);
            } else {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'cashfree'])->with(
                    'error',
                    $response['message'] ?? trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'cashfree'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'cashfree', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            if (isset($request['referenceId']) && $request['txStatus'] == 'SUCCESS') {
                $this->paymentService->payment($order, 'cashfree', $request['referenceId']);
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            } else {
                return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'cashfree'])->with(
                    'error',
                    $request['txMsg']
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'cashfree'])->with(
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
