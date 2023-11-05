<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Models\MenuTemplate;
use App\Services\MenuTemplateService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\MenuTemplateRequest;
use App\Http\Resources\MenuTemplateResource;


class MenuTemplateController extends AdminController
{

    private MenuTemplateService $menuTemplateService;

    public function __construct(MenuTemplateService $menuTemplate)
    {
        parent::__construct();
        $this->menuTemplateService = $menuTemplate;
    }

    public function index(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return MenuTemplateResource::collection($this->menuTemplateService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(MenuTemplateRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | MenuTemplateResource | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new MenuTemplateResource($this->menuTemplateService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(MenuTemplate $menuTemplate
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | MenuTemplateResource | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new MenuTemplateResource($this->menuTemplateService->show($menuTemplate));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(
        MenuTemplateRequest $request,
        MenuTemplate $menuTemplate
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | MenuTemplateResource | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new MenuTemplateResource($this->menuTemplateService->update($request, $menuTemplate));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(MenuTemplate $menuTemplate
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->menuTemplateService->destroy($menuTemplate);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
