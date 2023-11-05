<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\ItemCategory;
use App\Http\Requests\PaginateRequest;

class PosCategoryController extends AdminController
{

    protected $itemCateFilter = [
        'name',
        'slug',
        'description',
        'status'
    ];

    protected $exceptFilter = [
        'excepts'
    ];

    public function index(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            $itemCategories =  ItemCategory::with('media')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->itemCateFilter)) {
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
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );

            $itemCategoryArray = [];

            $addArray[] = [
                'id'          => 0,
                'name'        =>  trans('all.label.all_items'),
                'slug'        => 'all-items',
                'thumb'       => asset("images/default/all-category.png"),
                'cover'       => asset("images/default/all-category.png")
            ];
            foreach ($itemCategories as $itemCategory) {
                $itemCategoryArray[] = [
                    'id'          => $itemCategory->id,
                    'name'        => $itemCategory->name,
                    'slug'        => $itemCategory->slug,
                    'description' => $itemCategory->description === null ? '' : $itemCategory->description,
                    'status'      => $itemCategory->status,
                    'thumb'       => $itemCategory->thumb,
                    'cover'       => $itemCategory->cover
                ];
            }

            $newObj = array_merge($addArray, $itemCategoryArray);

            return ['data'  => $newObj];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
