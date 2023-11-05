<?php

namespace App\Exports;

use App\Enums\DiscountType;
use App\Http\Requests\PaginateRequest;
use App\Libraries\AppLibrary;
use App\Services\CouponService;
use App\Services\DeliveryBoyService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CouponExport implements FromCollection, WithHeadings
{

    public CouponService $couponService;
    public PaginateRequest $request;

    public function __construct(CouponService $couponService, $request)
    {
        $this->couponService = $couponService;
        $this->request            = $request;
    }

    public function collection() : \Illuminate\Support\Collection
    {
        $couponArray = [];
        $couponsArray     = $this->couponService->list($this->request);

        foreach ($couponsArray as $coupon) {
            $couponArray[] = [
                $coupon->name,
                $coupon->code,
                $coupon->discount,
                $coupon->discount_type == DiscountType::FIXED ? 'Fixed' : 'Percentage',
                AppLibrary::datetime($coupon->start_date),
                AppLibrary::datetime($coupon->end_date),
            ];
        }
        return collect($couponArray);
    }

    public function headings() : array
    {
        return [
            trans('all.label.name'),
            trans('all.label.code'),
            trans('all.label.discount'),
            trans('all.label.discount_type'),
            trans('all.label.start_date'),
            trans('all.label.end_date'),
        ];
    }
}
