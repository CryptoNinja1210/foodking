<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Item;
use App\Models\ItemExtra;
use App\Services\ItemExtraService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ItemExtraRequest;
use App\Http\Resources\ItemExtraResource;

class ItemExtraController extends AdminController
{
    public ItemExtraService $itemExtraService;

    public function __construct(ItemExtraService $itemExtraService)
    {
        parent::__construct();
        $this->itemExtraService = $itemExtraService;
        $this->middleware(['permission:items_show'])->only('index', 'show', 'store', 'update', 'destroy');
    }

    public function index(PaginateRequest $request, Item $item) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemExtraResource::collection($this->itemExtraService->list($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function store(ItemExtraRequest $request, Item $item) : ItemExtraResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemExtraResource($this->itemExtraService->store($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(ItemExtraRequest $request, Item $item, ItemExtra $itemExtra) : ItemExtraResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemExtraResource($this->itemExtraService->update($request, $item, $itemExtra));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function show(Item $item, ItemExtra $itemExtra) : ItemExtraResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemExtraResource($this->itemExtraService->show($item, $itemExtra));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Item $item, ItemExtra $itemExtra) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->itemExtraService->destroy($item, $itemExtra);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
