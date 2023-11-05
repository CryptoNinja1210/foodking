<?php

namespace App\Services;



use Carbon\Carbon;
use Exception;
use App\Models\Coupon;
use App\Libraries\AppLibrary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CouponRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\CouponCheckRequest;

class CouponService
{
    public $coupon;
    protected $couponFilter = [
        'name',
        'code',
        'discount',
        'discount_type',
        'start_date',
        'end_date',
        'minimum_order',
        'maximum_discount',
        'limit_per_user',
    ];

    protected $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return Coupon::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->couponFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(CouponRequest $request)
    {
        try {
            $this->coupon = Coupon::create([
                'name'             => $request->name,
                'description'      => $request->description,
                'code'             => $request->code,
                'discount'         => $request->discount,
                'discount_type'    => $request->discount_type,
                'start_date'       => !blank($request->start_date) ? date(
                    'Y-m-d H:i:s',
                    strtotime($request->start_date)
                ) : "",
                'end_date'         => !blank($request->end_date) ? date(
                    'Y-m-d H:i:s',
                    strtotime($request->end_date)
                ) : "",
                'minimum_order'    => $request->minimum_order,
                'maximum_discount' => $request->maximum_discount,
                'limit_per_user'   => $request->limit_per_user,
            ]);
            if ($request->image) {
                $this->coupon->addMedia($request->image)->toMediaCollection('coupon');
            }
            return $this->coupon;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        try {
            DB::transaction(function () use ($request, $coupon) {
                $this->coupon             = $coupon;
                $coupon->name             = $request->name;
                $coupon->description      = $request->description;
                $coupon->code             = $request->code;
                $coupon->discount         = $request->discount;
                $coupon->discount_type    = $request->discount_type;
                $coupon->start_date       = !blank($request->start_date) ? date(
                    'Y-m-d H:i:s',
                    strtotime($request->start_date)
                ) : null;
                $coupon->end_date         = !blank($request->end_date) ? date(
                    'Y-m-d H:i:s',
                    strtotime($request->end_date)
                ) : null;
                $coupon->minimum_order    = $request->minimum_order;
                $coupon->maximum_discount = $request->maximum_discount;
                $coupon->limit_per_user   = $request->limit_per_user;
                $coupon->save();
                if ($request->image) {
                    $coupon->media()->delete();
                    $coupon->addMedia($request->image)->toMediaCollection('coupon');
                }
            });
            return $this->coupon;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Coupon $coupon): Coupon
    {
        try {
            return $coupon;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function couponDateWise(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return Coupon::all()->filter(function ($item) {
                if (Carbon::now()->between($item->start_date, $item->end_date)) {
                    return $item;
                }
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function couponChecking(CouponCheckRequest $request)
    {
        try {
            $coupon = Coupon::where(['code' => $request->code])->first();
            if ($coupon) {
                if ($coupon->minimum_order > $request->total) {
                    throw new Exception(trans('all.message.minimum_order_amount') . AppLibrary::convertAmountFormat($coupon->minimum_order), 422);
                } else {
                    if (strtotime($coupon->end_date) >= strtotime(Carbon::now())) {
                        return $coupon;
                    } else {
                        throw new Exception(trans('all.message.coupon_date_expired'), 422);
                    }
                }
            } else {
                throw new Exception(trans('all.message.coupon_not_exist'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}