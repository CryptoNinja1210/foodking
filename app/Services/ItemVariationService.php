<?php

namespace App\Services;


use Exception;
use App\Models\Item;
use App\Models\ItemVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ItemVariationRequest;

class ItemVariationService
{
    public $itemVariation;
    protected $itemVariationFilter = [
        'item_id',
        'item_attribute_id',
        'price',
        'status'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, Item $item)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return ItemVariation::with('item', 'itemAttribute')->where(['item_id' => $item->id])->where(
                function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->itemVariationFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function listGroupByAttribute(PaginateRequest $request, Item $item): \Illuminate\Support\Collection
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            $itemVariations = ItemVariation::with('item', 'itemAttribute')->where(['item_id' => $item->id])->where(
                function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->itemVariationFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );

            $array = [];
            if ($itemVariations) {
                foreach ($itemVariations as $itemVariation) {
                    if (!isset($array[$itemVariation->item_attribute_id])) {
                        $array[$itemVariation->item_attribute_id] = (object)[
                            'item_attribute_id' => $itemVariation->item_attribute_id,
                            'item_attribute'    => $itemVariation->itemAttribute,
                            'children'          => [
                                (object)[
                                    'id'                => $itemVariation->id,
                                    'item_id'           => $itemVariation->item_id,
                                    'item_attribute_id' => $itemVariation->item_attribute_id,
                                    'name'              => $itemVariation->name,
                                    'price'             => $itemVariation->price,
                                    'caution'           => $itemVariation->caution,
                                    'status'            => $itemVariation->status,
                                    'item'              => $itemVariation->item,
                                    'itemAttribute'     => $itemVariation->itemAttribute
                                ]
                            ]
                        ];
                    } else {
                        $array[$itemVariation->item_attribute_id]->children[] = (object)[
                            'id'                => $itemVariation->id,
                            'item_id'           => $itemVariation->item_id,
                            'item_attribute_id' => $itemVariation->item_attribute_id,
                            'name'              => $itemVariation->name,
                            'price'             => $itemVariation->price,
                            'caution'           => $itemVariation->caution,
                            'status'            => $itemVariation->status,
                            'item'              => $itemVariation->item,
                            'itemAttribute'     => $itemVariation->itemAttribute
                        ];
                    }
                }
            }
            return collect($array);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(ItemVariationRequest $request, Item $item)
    {
        try {
            return ItemVariation::create($request->validated() + ['item_id' => $item->id]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ItemVariationRequest $request, Item $item, ItemVariation $itemVariation): ItemVariation
    {
        try {
            if ($item->id == $itemVariation->item_id) {
                return tap($itemVariation)->update($request->validated());
            }
            return $itemVariation;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function destroy(Item $item, ItemVariation $itemVariation): void
    {
        try {
            if ($item->id == $itemVariation->item_id) {
                $itemVariation->delete();
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function show(Item $item, ItemVariation $itemVariation)
    {
        try {
            return ItemVariation::where(['item_id' => $item->id, 'id' => $itemVariation->id])->first();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    private function recursiveDelete($variation)
    {
        $itemId = $variation->item_id;
        $variation->delete();

        $Variations = ItemVariation::where(['item_id' => $itemId])->get();
        foreach ($Variations as $Variation) {
            $this->recursiveDelete($Variation);
        }
    }
}
