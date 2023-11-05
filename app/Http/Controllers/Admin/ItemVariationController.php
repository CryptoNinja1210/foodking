<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ItemVariationGroupByAttributeResource;
use Exception;
use App\Models\Item;
use App\Http\Requests\PaginateRequest;
use App\Services\ItemVariationService;
use App\Http\Requests\ItemVariationRequest;
use App\Http\Resources\ItemVariationResource;
use App\Models\ItemVariation;

class ItemVariationController extends AdminController
{
    public ItemVariationService $itemVariationService;

    public function __construct(ItemVariationService $itemVariationService)
    {
        parent::__construct();
        $this->itemVariationService = $itemVariationService;
        $this->middleware(['permission:items_show'])->only('index', 'listGroupByAttribute', 'show', 'store', 'update', 'destroy');
    }

    public function index(PaginateRequest $request, Item $item) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemVariationResource::collection($this->itemVariationService->list($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function listGroupByAttribute(PaginateRequest $request, Item $item): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemVariationGroupByAttributeResource::collection($this->itemVariationService->listGroupByAttribute($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function store(ItemVariationRequest $request, Item $item): \Illuminate\Http\Response | ItemVariationResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemVariationResource($this->itemVariationService->store($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(ItemVariationRequest $request, Item $item, ItemVariation $itemVariation): \Illuminate\Http\Response | ItemVariationResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemVariationResource($this->itemVariationService->update($request, $item, $itemVariation));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function show(Item $item, ItemVariation $itemVariation): \Illuminate\Http\Response | ItemVariationResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemVariationResource($this->itemVariationService->show($item, $itemVariation));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function destroy(Item $item, ItemVariation $itemVariation): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->itemVariationService->destroy($item, $itemVariation);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
