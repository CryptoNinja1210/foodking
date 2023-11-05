<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Services\ItemCategoryService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ItemCategoryRequest;
use App\Http\Resources\ItemCategoryResource;
use App\Models\ItemCategory;

class ItemCategoryController extends AdminController
{
    private ItemCategoryService $itemCategoryService;

    public function __construct(ItemCategoryService $itemCategory)
    {
        parent::__construct();
        $this->itemCategoryService = $itemCategory;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy', 'show');
    }

    public function index(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return ItemCategoryResource::collection($this->itemCategoryService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function store(ItemCategoryRequest $request
    ) : \Illuminate\Http\Response | ItemCategoryResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ItemCategoryResource($this->itemCategoryService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(ItemCategory $itemCategory
    ) : \Illuminate\Http\Response | ItemCategoryResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ItemCategoryResource($this->itemCategoryService->show($itemCategory));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(
        ItemCategoryRequest $request,
        ItemCategory $itemCategory
    ) : \Illuminate\Http\Response | ItemCategoryResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ItemCategoryResource($this->itemCategoryService->update($request, $itemCategory));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(ItemCategory $itemCategory
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->itemCategoryService->destroy($itemCategory);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
