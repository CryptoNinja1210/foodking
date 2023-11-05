<?php

namespace App\Http\Controllers\Admin;

use App\Models\AnalyticSection;
use Exception;
use App\Models\Analytic;
use App\Http\Requests\PaginateRequest;
use App\Services\AnalyticSectionService;
use App\Http\Requests\AnalyticSectionRequest;
use App\Http\Resources\AnalyticSectionResource;

class AnalyticSectionController extends AdminController
{
    private AnalyticSectionService $analyticSectionService;

    public function __construct(AnalyticSectionService $analyticSectionService)
    {
        parent::__construct();
        $this->analyticSectionService = $analyticSectionService;
    }

    public function index(PaginateRequest $request, Analytic $analytic) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return AnalyticSectionResource::collection($this->analyticSectionService->list($request, $analytic));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function store(AnalyticSectionRequest $request, Analytic $analytic) : \Illuminate\Http\Response | AnalyticSectionResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new AnalyticSectionResource($this->analyticSectionService->store($request, $analytic));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(AnalyticSectionRequest $request, Analytic $analytic, AnalyticSection $analyticSection) : \Illuminate\Http\Response | AnalyticSectionResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new AnalyticSectionResource($this->analyticSectionService->update($request, $analytic, $analyticSection));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Analytic $analytic, AnalyticSection $analyticSection) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->analyticSectionService->destroy($analytic, $analyticSection);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Analytic $analytic, AnalyticSection $analyticSection) : \Illuminate\Http\Response | AnalyticSectionResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new AnalyticSectionResource($this->analyticSectionService->show($analytic, $analyticSection));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
