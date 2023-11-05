<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Libraries\AppLibrary;
use App\Services\ItemService;
use App\Services\DashboardService;
use App\Http\Resources\ItemResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Resources\OrderSummaryResource;
use App\Http\Resources\SalesSummaryResource;
use App\Http\Resources\CustomerStatesResource;
use App\Http\Resources\OrderStatisticsResource;

class DashboardController extends AdminController
{
    private DashboardService $dashboardService;
    private ItemService $itemService;

    public function __construct(DashboardService $dashboardService, ItemService $itemService)
    {
        parent::__construct();
        $this->dashboardService = $dashboardService;
        $this->itemService      = $itemService;
        $this->middleware(['permission:dashboard'])->only(
            'orderStatistics',
            'orderSummary',
            'featuredItems',
            'mostPopularItems',
            'topCustomers',
            'totalSales',
            'salesSummary',
            'customerStates',
            'totalOrders',
            'totalCustomers',
            'totalMenuItems'
        );
    }

    public function totalSales(
    ) : \Illuminate\Http\Response | array | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ['data' => ['total_sales' => AppLibrary::flatAmountFormat($this->dashboardService->totalSales())]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function totalOrders(
    ) : \Illuminate\Http\Response | array | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ['data' => ['total_orders' => $this->dashboardService->totalOrders()]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function totalCustomers(
    ) : \Illuminate\Http\Response | array | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ['data' => ['total_customers' => $this->dashboardService->totalCustomers()]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function totalMenuItems(
    ) : \Illuminate\Http\Response | array | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ['data' => ['total_menu_items' => $this->dashboardService->totalMenuItems()]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function orderStatistics(Request $request
    ) : \Illuminate\Http\Response | OrderStatisticsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new OrderStatisticsResource($this->dashboardService->orderStatistics($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function salesSummary(Request $request
    ) : \Illuminate\Http\Response | SalesSummaryResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new SalesSummaryResource($this->dashboardService->salesSummary($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function orderSummary(Request $request
    ) : \Illuminate\Http\Response | OrderSummaryResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new OrderSummaryResource($this->dashboardService->orderSummary($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function customerStates(Request $request
    ) : \Illuminate\Http\Response | CustomerStatesResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new CustomerStatesResource($this->dashboardService->customerStates($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function topCustomers(
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return UserResource::collection($this->dashboardService->topCustomers());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function featuredItems(
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemResource::collection($this->itemService->featuredItems());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function mostPopularItems(
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemResource::collection($this->itemService->mostPopularItems());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
