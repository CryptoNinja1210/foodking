<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PushNotificationExport;
use Exception;
use App\Models\PushNotification;
use App\Services\PushNotificationService;
use App\Http\Requests\PushNotificationRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PushNotificationResource;
use Maatwebsite\Excel\Facades\Excel;

class PushNotificationController extends AdminController
{
    private PushNotificationService $pushNotificationService;

    public function __construct(PushNotificationService $pushNotificationService)
    {
        parent::__construct();
        $this->pushNotificationService = $pushNotificationService;
        $this->middleware(['permission:push-notifications'])->only('index', 'export');
        $this->middleware(['permission:push-notifications_create'])->only('store');
        $this->middleware(['permission:push-notifications_delete'])->only('destroy');
        $this->middleware(['permission:push-notifications_show'])->only('show');
    }

    public function index(PaginateRequest $request) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return PushNotificationResource::collection($this->pushNotificationService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(PushNotificationRequest $request) : \Illuminate\Http\Response | PushNotificationResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new PushNotificationResource($this->pushNotificationService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(PushNotification $pushNotification) : \Illuminate\Http\Response | PushNotificationResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new PushNotificationResource($this->pushNotificationService->show($pushNotification));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(PushNotification $pushNotification) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->pushNotificationService->destroy($pushNotification);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request) : \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new PushNotificationExport($this->pushNotificationService, $request), 'Push-Notification.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
