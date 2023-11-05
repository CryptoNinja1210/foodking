<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Services\PageService;
use App\Http\Requests\PageRequest;
use App\Http\Resources\PageResource;
use App\Http\Requests\PaginateRequest;
use App\Models\Page;

class PageController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private PageService $pageService;

    public function __construct(PageService $page)
    {
        parent::__construct();
        $this->pageService = $page;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy', 'show');
    }

    public function index(PaginateRequest $request)
    {
        try {
            return PageResource::collection($this->pageService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request): \Illuminate\Http\Response | PageResource
    {
        try {
            return new PageResource($this->pageService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page): \Illuminate\Http\Response | PageResource
    {
        try {
            return new PageResource($this->pageService->show($page));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, Page $page): \Illuminate\Http\Response | PageResource
    {
        try {
            return new PageResource($this->pageService->update($request, $page));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        try {
            $this->pageService->destroy($page);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}