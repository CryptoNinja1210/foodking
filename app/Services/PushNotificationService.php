<?php

namespace App\Services;


use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\PushNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PushNotificationRequest;

class PushNotificationService
{
    public object $pushNotification;
    protected array $pushNotificationFilter = [
        'title',
        'role_id',
        'user_id'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return PushNotification::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->pushNotificationFilter)) {
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
    public function store(PushNotificationRequest $request) : PushNotification
    {
        try {
            $pushNotification              = new PushNotification();
            $pushNotification->title       = $request->title;
            $pushNotification->description = strip_tags($request->description);
            $pushNotification->role_id     = $request->role_id ?? 0;
            $pushNotification->user_id     = $request->user_id ?? 0;
            $pushNotification->branch_id   = $request->branch_id;
            $pushNotification->save();

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $pushNotification->clearMediaCollection('pushNotifications');
                $pushNotification->addMediaFromRequest('image')->toMediaCollection('pushNotifications');
            }

            if ($pushNotification->role_id == 0 && $pushNotification->user_id == 0) {
                $fcmWebDeviceToken    = User::whereNotNull('web_token')->pluck('web_token')->toArray();
                $fcmMobileDeviceToken = User::whereNotNull('device_token')->pluck('device_token')->toArray();
            } else {
                if ($pushNotification->role_id !== 0 && $pushNotification->user_id == 0) {
                    $fcmWebDeviceToken    = User::role(
                        $pushNotification->role_id
                    )->whereNotNull('web_token')->pluck('web_token')->toArray();
                    $fcmMobileDeviceToken = User::role(
                        $pushNotification->role_id
                    )->whereNotNull('device_token')->pluck('device_token')->toArray();
                } else {
                    $fcmWebDeviceToken    = User::where(['id' => $pushNotification->user_id])->whereNotNull(
                        'web_token'
                    )->pluck('web_token')->toArray();
                    $fcmMobileDeviceToken = User::where(['id' => $pushNotification->user_id])->whereNotNull(
                        'device_token'
                    )->pluck('device_token')->toArray();
                }
            }

            $fcmTokenArray = array_merge($fcmWebDeviceToken, $fcmMobileDeviceToken);
            $firebase      = new FirebaseService();
            $firebase->sendNotification($pushNotification, $fcmTokenArray, "promotion");
            return $pushNotification;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(PushNotification $pushNotification) : PushNotification
    {
        try {
            return $pushNotification;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(PushNotification $pushNotification)
    {
        try {
            DB::transaction(function () use ($pushNotification) {
                $pushNotification->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function sendPushNotification($data, $fcmToken, $topicName) : Exception | string
    {
        if (!empty($topicName)) {
            $topic = env('FCM_TOPIC') . '_' . str_replace(['@', '.', '+'], ['_', '_', ''], $topicName);
        } else {
            $topic = env('FCM_TOPIC');
        }

        $final = [
            'registration_ids' => $fcmToken,
            'priority'         => 'high',
            'notification'     => [
                'title' => $data->title,
                'body'  => $data->description,
                'sound' => 'Default',
                'image' => $data->image,
            ],
            'data'             => [
                'title' => $data->title,
                'body'  => $data->description,
                'sound' => 'Default',
                'image' => $data->image
            ]
        ];

        $url = 'https://fcm.googleapis.com/fcm/send';

        $client  = new Client();
        $headers = [
            'Authorization' => "key=" . env('FCM_SECRET_KEY'),
            'Content-Type'  => 'application/json',
        ];

        try {
            $result = $client->post($url, [
                'headers' => $headers,
                "body"    => json_encode($final)
            ]);
            return $result->getBody()->getContents();
        } catch (Exception $e) {
            return $e;
        }
    }
}
