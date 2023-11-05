<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Transaction;
use App\Models\User;

class PaymentService
{
    public function payment($order, $gatewaySlug, $transactionNo)
    {
        $transaction = Transaction::where(['order_id' => $order->id])->first();
        if (!$transaction) {
            $transaction = Transaction::create([
                'order_id'       => $order->id,
                'transaction_no' => $transactionNo,
                'amount'         => $order->total,
                'payment_method' => $gatewaySlug,
                'sign'           => '+',
                'type'           => 'payment'
            ]);
        }
        $order->payment_status = PaymentStatus::PAID;
        $order->save();
        return $transaction;
    }

    public function cashBack($order, $gatewaySlug, $transactionNo)
    {
        $transaction = Transaction::where(['order_id' => $order->id])->first();
        if ($transaction) {
            $transaction = Transaction::create([
                'order_id'       => $order->id,
                'transaction_no' => $transactionNo,
                'amount'         => $order->total,
                'payment_method' => $gatewaySlug,
                'sign'           => '-',
                'type'           => 'cash_back'
            ]);

            $user = User::find($order->user_id);
            if ($user) {
                $user->balance = ($user->balance + $order->total);
                $user->save();
            }
        }

        return $transaction;
    }
}
