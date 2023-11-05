<?php

namespace App\Services;


use Exception;
use App\Models\ItemAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ItemAttributeRequest;

class ItemAttributeService
{
    public $itemAttribute;
    protected $itemAttributeFilter = [
        'name',
        'status'
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

            return ItemAttribute::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->itemAttributeFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
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
    public function store(ItemAttributeRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->itemAttribute = ItemAttribute::create($request->validated());
            });
            return $this->itemAttribute;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ItemAttributeRequest $request, ItemAttribute $itemAttribute): ItemAttribute
    {
        try {
            DB::transaction(function () use ($request, $itemAttribute) {
                $itemAttribute->update($request->validated());
            });
            return $itemAttribute;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(ItemAttribute $itemAttribute)
    {
        try {
            DB::transaction(function () use ($itemAttribute) {
                $itemAttribute->delete();
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
    public function show(ItemAttribute $itemAttribute): ItemAttribute
    {
        try {
            return $itemAttribute;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
