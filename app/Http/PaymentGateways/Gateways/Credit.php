<?php

namespace App\Http\PaymentGateways\Gateways;

use App\Enums\Activity;
use App\Models\CapturePaymentNotification;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Services\PaymentAbstract;
use App\Services\PaymentService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Credit extends PaymentAbstract
{
    public bool $response = false;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
    }

    public function payment($order, $request) : \Illuminate\Http\RedirectResponse
    {
        try {
            if ($order?->user?->balance >= $order->total) {
                $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                    ['order_id', $order->id]
                ]);
                $capturePaymentNotification?->delete();
                $token = rand(111111111, 999999999);
                CapturePaymentNotification::create([
                    'order_id'   => $order->id,
                    'token'      => $token,
                    'created_at' => now()
                ]);

                return redirect()->away(
                    route('payment.success', ['paymentGateway' => 'credit', 'order' => $order, 'token' => $token])
                );
            } else {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'credit'])->with(
                    'error',
                    trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'credit'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status() : bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'credit', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request) : \Illuminate\Http\RedirectResponse
    {
        try {
            DB::transaction(function () use ($order, $request) {
                if ($request->token) {
                    $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                        ['token', $request->token]
                    ]);
                    $token                      = $capturePaymentNotification->first();
                    if (!blank($token) && $order->id == $token->order_id) {
                        $user = User::find($order->user_id);
                        if ($user) {
                            $user->balance = ($user->balance - $order->total);
                            $user->save();
                            $this->paymentService->payment($order, 'credit', $token->token);
                            $capturePaymentNotification->delete();
                            $this->response = true;
                        }
                    }
                }
            });

            if ($this->response) {
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            }
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'credit'])->with(
                'error',
                trans('all.message.something_wrong')
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'credit'])->with(
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
