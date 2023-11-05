<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SiteRequest;
use App\Http\Resources\SiteResource;
use App\Services\SiteService;
use Exception;

class SiteController extends AdminController
{
    public SiteService $siteService;

    public function __construct(SiteService $siteService)
    {
        parent::__construct();
        $this->siteService = $siteService;
        $this->middleware(['permission:settings'])->only('update');
    }

    public function index(): SiteResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new SiteResource($this->siteService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(SiteRequest $request): SiteResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new SiteResource($this->siteService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
