<?php

namespace App\Services;

trait PushNotificationService
{
    public function sendNotification($token, $title, $message, $payload = null, $image = '')
    {
        $data = [
            "to" => $token,
            "notification" => [
                "title" => $title,
                "body" => $message
            ],
            "data" => $payload,
            "android" => [
                "notification" => [
                    "imageUrl" => $image,
                    "click_action" => "TOP_STORY_ACTIVITY"

                ]
            ],
            "apns" => [
                "payload" => [
                    "aps" => [
                        "category" => "NEW_MESSAGE_CATEGORY"
                    ]
                ]
            ]

        ];

        $this->pushNotification($data);
    }

    public function sendNotifications($tokens, $title, $message)
    {
        foreach ($tokens as $token) {
            $this->sendNotification($token, $title, $message);
        }
    }

    public function pushNotification($request)
    {
        $headers = [
            'Authorization: key=' . config('app_greenchoicefund.firebase_server_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

        $response = curl_exec($ch);
    }
}
