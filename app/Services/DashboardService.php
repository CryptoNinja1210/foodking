<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Libraries\AppLibrary;
use App\Enums\Role as EnumRole;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    /**
     * @throws Exception
     */
    public function orderStatistics(Request $request)
    {
        try {
            $order = new Order;

            if ($request->first_date && $request->last_date) {
                $first_date = Date('Y-m-d', strtotime($request->first_date));
                $last_date  = Date('Y-m-d', strtotime($request->last_date));
            } else {
                $first_date = Carbon::today()->toDateString();
                $last_date  = Carbon::today()->toDateString();
            }

            $orderStatisticsArray = [];

            $orderStatisticsArray["total_order"]            = $order->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["pending_order"]          = $order->pending()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["processing_order"]       = $order->processing()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["out_for_delivery_order"] = $order->outForDelivery()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["delivered_order"]        = $order->delivered()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["canceled_order"]         = $order->canceled()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["returned_order"]         = $order->returned()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["rejected_order"]         = $order->rejected()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();

            return $orderStatisticsArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    public function orderSummary(Request $request)
    {
        try {
            $order = new Order;
            if ($request->first_date && $request->last_date) {
                $first_date = Date('Y-m-d', strtotime($request->first_date));
                $last_date  = Date('Y-m-d', strtotime($request->last_date));
            } else {
                $first_date = Date('Y-m-01', strtotime(Carbon::today()->toDateString()));
                $last_date  = Date('Y-m-t', strtotime(Carbon::today()->toDateString()));
            }

            $orderSummaryArray = [];

            $total_order   = $order->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $total_delivered = $order->delivered()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $total_canceled  = $order->canceled()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $total_returned  = $order->returned()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $total_rejected  = $order->rejected()->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();


            if ($total_order > 0) {
                $orderSummaryArray["delivered"] = (int) round(($total_delivered * 100) / $total_order);
                $orderSummaryArray["returned"]  = (int) round(($total_returned * 100) / $total_order);
                $orderSummaryArray["canceled"] = (int) round(($total_canceled * 100) / $total_order);
                $orderSummaryArray["rejected"] = (int) round(($total_rejected * 100) / $total_order);
            } else {
                $orderSummaryArray["delivered"] = 0;
                $orderSummaryArray["returned"]  = 0;
                $orderSummaryArray["canceled"] = 0;
                $orderSummaryArray["rejected"] = 0;
            }

            return $orderSummaryArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function salesSummary(Request $request)
    {
        $order = new Order;
        if ($request->first_date && $request->last_date) {
            $first_date = Date('Y-m-d', strtotime($request->first_date));
            $last_date  = Date('Y-m-d', strtotime($request->last_date));
        } else {
            $first_date = Date('Y-m-01', strtotime(Carbon::today()->toDateString()));
            $last_date  = Date('Y-m-t', strtotime(Carbon::today()->toDateString()));
        }

        $date = date_diff(date_create($first_date), date_create($last_date), false);
        $date_diff = (int)$date->format("%a");

        $total_sales     = AppLibrary::flatAmountFormat($order->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->where('payment_status', PaymentStatus::PAID)->sum('total'));

        $dateRangeArray = [];
        for ($currentDate = strtotime($first_date); $currentDate <= strtotime($last_date); $currentDate += (86400)) {

            $date = date('Y-m-d', $currentDate);
            $dateRangeArray[] = $date;
        }

        $dateRangeValueArray = [];
        for ($i = 0; $i <= count($dateRangeArray) - 1; $i++) {
            $per_day     = AppLibrary::flatAmountFormat($order->whereDate('order_datetime', $dateRangeArray[$i])->where('payment_status', PaymentStatus::PAID)->sum('total'));
            $dateRangeValueArray[] = floatval($per_day);
        }


        $salesSummaryArray = [];
        if ($date_diff > 0) {
            $salesSummaryArray['total_sales']   = AppLibrary::currencyAmountFormat($total_sales);
            $salesSummaryArray['avg_per_day']   = AppLibrary::currencyAmountFormat($total_sales / $date_diff);
            $salesSummaryArray['per_day_sales'] = $dateRangeValueArray;
        } else {
            $salesSummaryArray['total_sales']   = AppLibrary::currencyAmountFormat($total_sales);
            $salesSummaryArray['avg_per_day']   = AppLibrary::currencyAmountFormat($total_sales);
            $salesSummaryArray['per_day_sales'] = $dateRangeValueArray;
        }

        return $salesSummaryArray;
    }

    public function customerStates(Request $request)
    {
        $order = new Order;
        if ($request->first_date && $request->last_date) {
            $first_date = Date('Y-m-d', strtotime($request->first_date));
            $last_date  = Date('Y-m-d', strtotime($request->last_date));
        } else {
            $first_date = Date('Y-m-01', strtotime(Carbon::today()->toDateString()));
            $last_date  = Date('Y-m-t', strtotime(Carbon::today()->toDateString()));
        }

        $timeArray = ["06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"];

        $customerSateArray = [];
        $totalCustomerArray = [];
        $first_time = "";
        $last_time = "";
        for ($i = 0; $i <= count($timeArray) - 1; $i++) {
            $first_time = date('H:i', strtotime($timeArray[$i]));
            $last_time = date('H:i', strtotime($timeArray[$i] . ' +59 minutes'));

            $total_customer     = $order->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->whereTime('order_datetime', '>=', Carbon::parse($first_time))->whereTime('order_datetime', '<=', Carbon::parse($last_time))->get()->count();
            $totalCustomerArray[] = $total_customer;
        }

        $customerSateArray['total_customers'] = $totalCustomerArray;
        $customerSateArray['times'] = $timeArray;

        return $customerSateArray;
    }

    public function topCustomers()
    {
        try {
            return User::withCount('orders')->orderBy('orders_count', 'desc')->role(EnumRole::CUSTOMER)->limit(8)->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function totalSales()
    {
        try {
            return Order::where('payment_status', PaymentStatus::PAID)->sum('total');
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function totalOrders()
    {
        try {
            return Order::where('status', OrderStatus::DELIVERED)->count();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function totalCustomers()
    {
        try {
            return User::role(EnumRole::CUSTOMER)->count();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function totalMenuItems()
    {
        try {
            return Item::count();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
