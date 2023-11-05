<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;
use Exception;

class CompanyController extends AdminController
{
    public CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        parent::__construct();
        $this->companyService = $companyService;
        $this->middleware(['permission:settings'])->only('update');
    }

    public function index() : \Illuminate\Http\Response | CompanyResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new CompanyResource($this->companyService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(CompanyRequest $request) : \Illuminate\Http\Response | CompanyResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new CompanyResource($this->companyService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
