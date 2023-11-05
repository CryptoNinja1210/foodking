<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Item;
use App\Models\ItemAddon;
use App\Services\ItemAddonService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ItemAddonRequest;
use App\Http\Resources\ItemAddonResource;

class ItemAddonController extends AdminController
{
    public ItemAddonService $itemAddonService;

    public function __construct(ItemAddonService $itemAddonService)
    {
        parent::__construct();
        $this->itemAddonService = $itemAddonService;
        $this->middleware(['permission:items_show'])->only('index', 'store', 'destroy');
    }

    public function index(PaginateRequest $request, Item $item) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemAddonResource::collection($this->itemAddonService->list($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function store(ItemAddonRequest $request, Item $item) : \Illuminate\Http\Response | ItemAddonResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemAddonResource($this->itemAddonService->store($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Item $item, ItemAddon $itemAddon) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->itemAddonService->destroy($item, $itemAddon);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
