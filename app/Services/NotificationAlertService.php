<?php

namespace App\Services;


use Exception;
use Illuminate\Http\Request;
use App\Models\NotificationAlert;
use Illuminate\Support\Facades\Log;

class NotificationAlertService
{
    /**
     * @throws Exception
     */
    public function list() : \Illuminate\Database\Eloquent\Collection
    {
        try {
            return NotificationAlert::all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request) : \Illuminate\Database\Eloquent\Collection
    {
        try {
            $type        = $request->type;
            $numberArray = [];
            $typeArray   = [];
            $alertCount  = NotificationAlert::count();
            foreach (range(1, $alertCount) as $number) {
                array_push($numberArray, $number);
                array_push($typeArray, $type . $number);
            }

            $data         = $request->only($numberArray);
            $option_Value = $request->only($typeArray);

            $id      = [];
            $message = [];
            $option  = [];

            foreach ($data as $key => $msg) {
                array_push($id, $key);
                array_push($message, $msg);
            }

            foreach ($option_Value as $value) {
                array_push($option, $value);
            }

            $notificationAlerts = [
                'id'      => $id,
                'message' => $message,
                'option'  => $option
            ];
            foreach ($notificationAlerts['id'] as $key => $notificationAlert) {
                NotificationAlert::where('id', $notificationAlert)->update([
                    $type . '_message' => $notificationAlerts['message'][$key],
                    $type              => $notificationAlerts['option'][$key],
                ]);
            }
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
