<?php

namespace App\Services;


use Exception;
use App\Models\Offer;
use App\Models\OfferItem;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\OfferItemRequest;

class OfferItemService
{
    public $itemExtra;
    protected $itemExtraFilter = [
        'item_id',
        'name',
        'price',
        'status'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, Offer $offer)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return OfferItem::with('offer', 'item')->where(['offer_id' => $offer->id])->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->itemExtraFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
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
    public function store(OfferItemRequest $request, Offer $offer)
    {

        try {
            $offerItem = OfferItem::where(['item_id' => $request->item_id])->first();
            if ($offerItem) {
                $previousOffer = Offer::where(['id' => $offerItem->offer_id])->first();
                if ($previousOffer->start_date >= $offer->start_date && $previousOffer->start_date > $offer->end_date) {
                    return OfferItem::create($request->validated() + ['offer_id' => $offer->id]);
                } else if ($previousOffer->end_date <= $offer->start_date && $previousOffer->end_date < $offer->end_date) {
                    return OfferItem::create($request->validated() + ['offer_id' => $offer->id]);
                } else {
                    throw new Exception(trans('all.message.offer_item_exist'), 422);
                }
            } else {
                return OfferItem::create($request->validated() + ['offer_id' => $offer->id]);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Offer $offer, OfferItem $offerItem)
    {
        try {
            if ($offer->id == $offerItem->offer_id) {
                $offerItem->delete();
            } else {
                throw new Exception(trans('all.item_match'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}