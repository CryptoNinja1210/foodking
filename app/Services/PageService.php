<?php

namespace App\Services;


use Exception;
use App\Models\Page;
use Illuminate\Support\Str;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;

class PageService
{
    protected $pageFilter = [
        'name',
        'slug',
        'description',
        'status',
        'menu_section_id'
    ];

    protected $exceptFilter = [
        'excepts'
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

            return Page::with('media')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->pageFilter)) {
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
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(PageRequest $request)
    {
        try {
            $page = Page::create($request->validated() + ['slug' => Str::slug($request->title)]);
            if ($request->image) {
                $page->addMediaFromRequest('image')->toMediaCollection('page-image');
            }
            return $page;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(PageRequest $request, Page $page): Page
    {
        try {
            $page->update($request->validated() + ['slug' => Str::slug($request->title)]);
            if ($request->image) {
                $page->clearMediaCollection('page-image');
                $page->addMediaFromRequest('image')->toMediaCollection('page-image');
            }
            return $page;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Page $page)
    {
        try {
            $page->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Page $page): Page
    {
        try {
            return $page;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}