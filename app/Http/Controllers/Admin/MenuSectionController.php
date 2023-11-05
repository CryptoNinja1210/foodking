<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\MenuSectionResource;
use App\Services\MenuSectionService;

class MenuSectionController extends AdminController
{

    private MenuSectionService $menuSectionService;

    public function __construct(MenuSectionService $menuSection)
    {
        parent::__construct();
        $this->menuSectionService = $menuSection;
    }

    public function index(PaginateRequest $request) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return MenuSectionResource::collection($this->menuSectionService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
