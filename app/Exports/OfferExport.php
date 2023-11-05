<?php

namespace App\Exports;

use App\Enums\DiscountType;
use App\Http\Requests\PaginateRequest;
use App\Libraries\AppLibrary;
use App\Services\CouponService;
use App\Services\DeliveryBoyService;
use App\Services\OfferService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OfferExport implements FromCollection, WithHeadings
{

    public OfferService $offerService;
    public PaginateRequest $request;

    public function __construct(OfferService $offerService, $request)
    {
        $this->offerService = $offerService;
        $this->request      = $request;
    }

    public function collection() : \Illuminate\Support\Collection
    {
        $offerArray  = [];
        $offersArray = $this->offerService->list($this->request);

        foreach ($offersArray as $offer) {
            $offerArray[] = [
                $offer->name,
                $offer->amount,
                AppLibrary::datetime($offer->start_date),
                AppLibrary::datetime($offer->end_date),
                trans('statuse.' . $offer->status),
            ];
        }
        return collect($offerArray);
    }

    public function headings() : array
    {
        return [
            trans('all.label.name'),
            trans('all.label.amount'),
            trans('all.label.start_date'),
            trans('all.label.end_date'),
            trans('all.label.status'),
        ];
    }
}
