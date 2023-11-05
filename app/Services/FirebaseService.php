<?php

namespace App\Services;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    public function sendNotification($data, $fcmToken, $topicName) : Exception | string
    {
        $final = [
            'registration_ids' => $fcmToken,
            'priority'         => 'high',
            'notification'     => [
                'title'     => $data->title,
                'body'      => $data->description,
                'sound'     => 'default',
                'image'     => $data->image ?? null,
                'topicName' => $topicName,
                'order_id'  => $data->order_id ?? null,
            ],
            'data'             => [
                'title'     => $data->title,
                'body'      => $data->description,
                'sound'     => 'default',
                'image'     => $data->image ?? null,
                'topicName' => $topicName,
                'order_id'  => $data->order_id ?? null,
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
            Log::info($e->getMessage());
            return $e->getMessage();
        }
    }
}
