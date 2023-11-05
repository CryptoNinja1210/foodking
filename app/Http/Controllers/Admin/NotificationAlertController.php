<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Services\NotificationAlertService;
use App\Http\Resources\NotificationAlertResource;

class NotificationAlertController extends AdminController
{
    private NotificationAlertService $notificationAlertService;

    public function __construct(NotificationAlertService $notificationAlertService)
    {
        parent::__construct();
        $this->notificationAlertService = $notificationAlertService;
        $this->middleware(['permission:settings'])->only('update');
    }

    public function index(
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return NotificationAlertResource::collection($this->notificationAlertService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(Request $request
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return NotificationAlertResource::collection($this->notificationAlertService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
