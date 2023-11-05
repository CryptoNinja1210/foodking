<?php

namespace App\Http\PaymentGateways\Gateways;


use App\Enums\Activity;
use App\Enums\GatewayMode;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Services\PaymentAbstract;
use Exception;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class Sslcommerz extends PaymentAbstract
{
    public mixed $response;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway       = PaymentGateway::with('gatewayOptions')->where(['slug' => 'sslcommerz'])->first();
        $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
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

            $array['store_id']         = $this->paymentGatewayOption['sslcommerz_store_id'];
            $array['store_passwd']     = $this->paymentGatewayOption['sslcommerz_store_password'];
            $array['total_amount']     = $order->total;
            $array['currency']         = $currencyCode;
            $array['tran_id']          = "SSLCZ_" . uniqid();
            $array['shipping_method']  = "NO";
            $array['cus_name']         = $order->user->name;
            $array['cus_email']        = $order->user->email;
            $array['cus_add1']         = $order->address;
            $array['cus_city']         = "";
            $array['cus_state']        = "";
            $array['cus_postcode']     = "";
            $array['cus_country']      = "";
            $array['cus_phone']        = $order->user->phone;
            $array['product_name']     = $order->order_serial_no;
            $array['product_category'] = "Food";
            $array['product_profile']  = "general";
            $array['product_amount']   = $order->subtotal;
            $array['discount_amount']  = $order->discount;
            $array['convenience_fee']  = $order->delivery_charge;
            $array['success_url']      = route('payment.success', [
                'order'          => $order,
                'paymentGateway' => 'sslcommerz'
            ]);
            $array['fail_url']         = route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'sslcommerz'
            ]);
            $array['cancel_url']       = route('payment.cancel', [
                'order'          => $order,
                'paymentGateway' => 'sslcommerz'
            ]);
            $apiUrl                    = $this->paymentGatewayOption['sslcommerz_mode'] == GatewayMode::SANDBOX ? "https://sandbox.sslcommerz.com/gwprocess/v4/api.php" : "https://securepay.sslcommerz.com/gwprocess/v4/api.php";

            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $apiUrl);
            curl_setopt($handle, CURLOPT_TIMEOUT, 30);
            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $array);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, !($this->paymentGatewayOption['sslcommerz_mode'] == GatewayMode::SANDBOX));

            $content = curl_exec($handle);
            $code    = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if ($code == 200 && !(curl_errno($handle))) {
                curl_close($handle);
                $sslcommerzResponse = $content;
            } else {
                curl_close($handle);
                return redirect()->route('payment.index', [
                    'order'          => $order,
                    'paymentGateway' => 'sslcommerz'
                ])->with('error', "Failed to connect with SSLCOMMERZ API");
            }

            $response = json_decode($sslcommerzResponse, true);
            if (isset($response['GatewayPageURL']) && $response['GatewayPageURL'] != "") {
                return redirect($response['GatewayPageURL']);
            } else {
                return redirect()->route('payment.index', [
                    'order'          => $order,
                    'paymentGateway' => 'sslcommerz'
                ])->with('error', "JSON Data parsing error!");
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', [
                'order'          => $order,
                'paymentGateway' => 'sslcommerz'
            ])->with('error', $e->getMessage());
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'sslcommerz', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            if (isset($request['bank_tran_id'])) {
                $paymentService = new PaymentService;
                $paymentService->payment($order, 'sslcommerz', $request['bank_tran_id']);
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } else {
                return redirect()->route('payment.fail', [
                    'order'          => $order,
                    'paymentGateway' => 'sslcommerz'
                ])->with('error', $this->response['message'] ?? trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'sslcommerz'
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
