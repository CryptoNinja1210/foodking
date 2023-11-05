<?php

namespace App\Services;


use App\Enums\Status;
use Exception;
use Carbon\Carbon;
use App\Models\Offer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OfferRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;

class OfferService
{
    public $offer;
    protected $offerFilter = [
        'name',
        'amount',
        'status',
        'start_date',
        'end_date',
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
            $limit       = $request->get('limit') ? $request->get('limit') : '';

            return Offer::with('items')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->offerFilter)) {
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
            })->limit($limit)->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function activeWise(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $limit       = $request->get('limit') ? $request->get('limit') : '';

            return Offer::with('items')->where('end_date', '>=', now()->toDateTimeString())->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->offerFilter)) {
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
            })->limit($limit)->orderBy($orderColumn, $orderType)->$method(
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
    public function store(OfferRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->offer = Offer::create([
                    'name'       => $request->name,
                    'slug'       => Str::slug($request->name),
                    'amount'     => $request->amount,
                    'start_date' => date('Y-m-d H:i:s', strtotime($request->start_date)),
                    'end_date'   => date('Y-m-d H:i:s', strtotime($request->end_date)),
                    'status'     => $request->status,
                ]);
                if ($request->image) {
                    $this->offer->addMedia($request->image)->toMediaCollection('offer');
                }
            });
            return $this->offer;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(OfferRequest $request, Offer $offer)
    {
        try {
            DB::transaction(function () use ($request, $offer) {
                $this->offer       = $offer;
                $offer->name       = $request->name;
                $offer->slug       = Str::slug($request->name);
                $offer->amount     = $request->amount;
                $offer->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
                $offer->end_date   = date('Y-m-d H:i:s', strtotime($request->end_date));
                $offer->status     = $request->status;
                $offer->save();
            });

            if ($request->image) {
                $this->offer->media()->delete();
                $this->offer->addMedia($request->image)->toMediaCollection('offer');
            }
            return $this->offer;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Offer $offer)
    {
        try {
            $offer->offerItems()->delete();
            $offer->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Offer $offer): Offer
    {
        try {
            return $offer;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeImage(ChangeImageRequest $request, Offer $offer): Offer
    {
        try {
            if ($request->image) {
                $offer->clearMediaCollection('offer');
                $offer->addMedia($request->image)->toMediaCollection('offer');
            }
            return $offer;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function offerItemByDate(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return Offer::with('offerItems')->where(['status' => Status::ACTIVE])->get()->filter(function ($offer) {
                if (Carbon::now()->between($offer->start_date, $offer->end_date)) {
                    return $offer;
                }
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
