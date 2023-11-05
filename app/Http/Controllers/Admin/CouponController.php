<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CouponExport;
use Exception;
use App\Services\CouponService;
use App\Http\Requests\CouponRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Maatwebsite\Excel\Facades\Excel;


class CouponController extends AdminController
{

    private CouponService $couponService;

    public function __construct(CouponService $coupon)
    {
        parent::__construct();
        $this->couponService = $coupon;
        $this->middleware(['permission:coupons'])->only('index', 'export');
        $this->middleware(['permission:coupons_create'])->only('store');
        $this->middleware(['permission:coupons_edit'])->only('update');
        $this->middleware(['permission:coupons_delete'])->only('destroy');
        $this->middleware(['permission:coupons_show'])->only('show');
    }

    public function index(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return CouponResource::collection($this->couponService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(CouponRequest $request) : CouponResource | \Illuminate\Http\Response
    {
        try {
            return new CouponResource($this->couponService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function show(Coupon $coupon
    ) : CouponResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new CouponResource($this->couponService->show($coupon));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(
        CouponRequest $request,
        Coupon $coupon
    ) : CouponResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new CouponResource($this->couponService->update($request, $coupon));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Coupon $coupon
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->couponService->destroy($coupon);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return Excel::download(new CouponExport($this->couponService, $request), 'Coupons.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
