<?php

namespace App\Http\Controllers\Frontend;


use Exception;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Services\OfferService;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\SimpleOfferListResource;
use App\Http\Resources\OfferItemByDateResource;

class OfferController extends Controller
{

    private OfferService $offerService;

    public function __construct(OfferService $offer)
    {
        $this->offerService = $offer;
    }

    public function index(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return SimpleOfferListResource::collection($this->offerService->activeWise($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function offerItemByDate(
        Request $request
    ): \Illuminate\Http\Response | OfferItemByDateResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new OfferItemByDateResource($this->offerService->offerItemByDate($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function offerItems(
        $slug
    ): \Illuminate\Http\Response | OfferResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $offer = Offer::where(['slug' => $slug])->first();
            return new OfferResource($this->offerService->show($offer));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
