<?php

namespace App\Services;


use Exception;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\TimeSlotRequest;

class TimeSlotService
{

    /**
     * @throws Exception
     */
    public $timeSlotFilter = ['opening_time', 'closing_time', 'day'];


    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return TimeSlot::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->timeSlotFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(TimeSlotRequest $request)
    {
        try {

            $check = TimeSlot::where('day', $request->day)->first();
            if (!$check) {
                return TimeSlot::create($request->validated());
            } else if ($check->opening_time >= $request->opening_time && $check->opening_time > $request->closing_time) {
                return TimeSlot::create($request->validated());
            } else if ($check->closing_time <= $request->opening_time && $check->closing_time < $request->closing_time) {
                return TimeSlot::create($request->validated());
            } else {
                throw new Exception(trans('all.message.time_slot_exist'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(TimeSlot $timeSlot): void
    {
        try {
            $timeSlot->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
