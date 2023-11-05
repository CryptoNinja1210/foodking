<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Services\ItemService;
use App\Exports\ItemsReportExport;
use App\Http\Resources\ItemResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PaginateRequest;

class ItemsReportController extends AdminController
{

    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        parent::__construct();
        $this->itemService = $itemService;
        $this->middleware(['permission:items-report'])->only('index', 'export');
    }

    public function index(PaginateRequest $request) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemResource::collection($this->itemService->itemReport($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request) : \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new ItemsReportExport($this->itemService, $request), 'Item-Report.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
