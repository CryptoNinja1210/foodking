<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\SimpleOfferListResource;
use Exception;
use App\Models\Offer;
use App\Exports\OfferExport;
use App\Services\OfferService;
use App\Http\Requests\OfferRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\OfferResource;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;


class OfferController extends AdminController
{

    private OfferService $offerService;

    public function __construct(OfferService $offer)
    {
        parent::__construct();
        $this->offerService = $offer;
        $this->middleware(['permission:offers'])->only('index', 'export', 'changeImage');
        $this->middleware(['permission:offers_create'])->only('store');
        $this->middleware(['permission:offers_edit'])->only('update');
        $this->middleware(['permission:offers_delete'])->only('destroy');
        $this->middleware(['permission:offers_show'])->only('show');
    }

    public function index(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return SimpleOfferListResource::collection($this->offerService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(OfferRequest $request) : \Illuminate\Http\Response | OfferResource
    {
        try {
            return new OfferResource($this->offerService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Offer $offer) : \Illuminate\Http\Response | OfferResource
    {
        try {
            return new OfferResource($this->offerService->show($offer));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(OfferRequest $request, Offer $offer) : \Illuminate\Http\Response | OfferResource
    {
        try {
            return new OfferResource($this->offerService->update($request, $offer));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Offer $offer) : \Illuminate\Http\Response
    {
        try {
            $this->offerService->destroy($offer);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return Excel::download(new OfferExport($this->offerService, $request), 'Offers.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changeImage(
        ChangeImageRequest $request,
        Offer $offer
    ) : \Illuminate\Http\Response | OfferResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new OfferResource($this->offerService->changeImage($request, $offer));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
