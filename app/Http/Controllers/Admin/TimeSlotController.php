<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\TimeSlot;
use App\Services\TimeSlotService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\TimeSlotRequest;
use App\Http\Resources\TimeSlotResource;

class TimeSlotController extends AdminController
{
    public TimeSlotService $timeSlotService;

    public function __construct(TimeSlotService $timeSlotService)
    {
        parent::__construct();
        $this->timeSlotService = $timeSlotService;
        $this->middleware(['permission:settings'])->only('store', 'destroy');
    }

    public function index(PaginateRequest $request)
    {
        try {
            return TimeSlotResource::collection($this->timeSlotService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(TimeSlotRequest $request)
    {
        try {
            return new TimeSlotResource($this->timeSlotService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(TimeSlot $timeSlot)
    {
        try {
            $this->timeSlotService->destroy($timeSlot);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}