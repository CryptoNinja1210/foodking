<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Services\CouponService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Http\Requests\CouponCheckRequest;
use App\Http\Resources\CouponCheckResource;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private CouponService $couponService;

    public function __construct(CouponService $coupon)
    {
        $this->couponService = $coupon;
    }

    public function index() : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return CouponResource::collection($this->couponService->couponDateWise());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function couponChecking(CouponCheckRequest $request) : \Illuminate\Http\Response | CouponCheckResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new CouponCheckResource($this->couponService->couponChecking($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
