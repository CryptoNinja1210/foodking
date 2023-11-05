<?php

namespace App\Http\Controllers\Frontend;


use Exception;
use App\Services\PageService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Http\Requests\PaginateRequest;
use App\Models\Page;

class PageController extends Controller
{
    private PageService $pageService;

    public function __construct(PageService $page)
    {
        $this->pageService = $page;
    }

    public function index(PaginateRequest $request) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return PageResource::collection($this->pageService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Page $page) : \Illuminate\Http\Response | PageResource
    {
        try {
            return new PageResource($this->pageService->show($page));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function pageInfo(Page $page) : \Illuminate\Http\Response | PageResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new PageResource($this->pageService->show($page));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
