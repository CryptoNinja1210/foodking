<?php

namespace App\Services;


use App\Enums\Ask;
use App\Enums\Status;
use Exception;
use App\Models\Item;
use Illuminate\Support\Str;
use App\Models\ItemVariation;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;

class ItemService
{
    public $item;
    protected $itemFilter = [
        'name',
        'slug',
        'item_category_id',
        'price',
        'is_featured',
        'item_type',
        'tax_id',
        'status',
        'order',
        'description',
        'except'
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

            return Item::with('media', 'category', 'tax')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->itemFilter)) {
                        if ($key == "except") {
                            $explodes = explode('|', $request);
                            if (count($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('id', '!=', $explode);
                                }
                            }
                        } else {
                            if ($key == "item_category_id") {
                                $query->where($key, $request);
                            } else {
                                $query->where($key, 'like', '%' . $request . '%');
                            }
                        }
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
    public function store(ItemRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->item = Item::create($request->validated() + ['slug' => Str::slug($request->name)]);
                if ($request->image) {
                    $this->item->addMedia($request->image)->toMediaCollection('item');
                }
                if ($request->variations) {
                    $this->item->variations()->createMany(json_decode($request->variations, true));
                }
            });
            return $this->item;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ItemRequest $request, Item $item): Item
    {
        try {
            DB::transaction(function () use ($request, $item) {
                $item->update($request->validated() + ['slug' => Str::slug($request->name)]);
                if ($request->image) {
                    $item->addMedia($request->image)->toMediaCollection('item');
                }
                if ($request->variations) {
                    $variationIdsArray    = [];
                    $variationDeleteArray = [];
                    $oldVariations        = $item->variations->pluck('id')->toArray();
                    foreach (json_decode($request->variations, true) as $variation) {
                        if (isset($variation['id'])) {
                            $variationIdsArray[] = $variation['id'];
                            ItemVariation::where('id', $variation['id'])->update([
                                'name'             => $variation['name'],
                                'price' => $variation['price'],
                            ]);
                        } else {
                            $item->variations()->create($variation);
                        }
                    }

                    if ($variationIdsArray) {
                        foreach ($oldVariations as $oldVariation) {
                            if (!in_array($oldVariation, $variationIdsArray)) {
                                $variationDeleteArray[] = $oldVariation;
                            }
                        }
                    }

                    if ($variationDeleteArray) {
                        ItemVariation::whereIn('id', $variationDeleteArray)->delete();
                    }
                }
            });
            return Item::find($item->id);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Item $item)
    {
        try {
            DB::transaction(function () use ($item) {
                $item->variations()->delete();
                $item->extras()->delete();
                $item->addons()->delete();
                $item->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Item $item): Item
    {
        try {
            return $item;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeImage(ChangeImageRequest $request, Item $item): Item
    {
        try {
            if ($request->image) {
                $item->clearMediaCollection('item');
                $item->addMedia($request->image)->toMediaCollection('item');
            }
            return $item;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function featuredItems()
    {
        try {
            return Item::where(['is_featured' => Ask::YES, 'status' => Status::ACTIVE])->inRandomOrder()->limit(8)->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function mostPopularItems()
    {
        try {
            return Item::withCount('orders')->where(['status' => Status::ACTIVE])->orderBy('orders_count', 'desc')->limit(6)->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function itemReport(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            return Item::withCount('orders')->where(function ($query) use ($requests) {
                if (isset($requests['from_date'])  && isset($requests['to_date'])) {
                    $first_date = date('Y-m-d', strtotime($requests['from_date']));
                    $last_date  = date('Y-m-d', strtotime($requests['to_date']));
                    $query->whereDate('created_at', '>=', $first_date)->whereDate(
                        'created_at',
                        '<=',
                        $last_date
                    );
                }
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->itemFilter)) {
                        if ($key == "except") {
                            $explodes = explode('|', $request);
                            if (count($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('id', '!=', $explode);
                                }
                            }
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                }
            })->orderBy('orders_count', 'desc')->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
