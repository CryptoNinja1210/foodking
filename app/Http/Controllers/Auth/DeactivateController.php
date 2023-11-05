<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Exception;


class DeactivateController extends Controller
{

    function deleteAccount(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $checkOrder = Order::where('user_id', auth()->user()->id)->whereNotIn('status', [OrderStatus::DELIVERED, OrderStatus::CANCELED, OrderStatus::REJECTED, OrderStatus::RETURNED])->first();
            if ($checkOrder) {
                throw new Exception(trans('all.message.account_not_delete'), 422);
            } else {
                $request->user()->addresses()->delete();
                $request->user()->delete();
                session()->flush();
                $request->user()->currentAccessToken()->delete();
                return response(['status' => true, 'message' => trans("all.message.account_delete_success")]);
            }
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
