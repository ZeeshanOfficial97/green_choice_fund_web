<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\AppUser\Web\AppUserResourceCollection;
use App\Http\Resources\Inquiry\Web\InquiryDetailResource;
use App\Http\Resources\Inquiry\Web\InquiryResourceCollection;
use App\Models\InstitutionInquiry;
use App\Models\User;
use App\Services\Web\AppUserService;
use App\Services\Web\InquiryService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use App\Events\BroadcastNotificationEvent;
use App\Models\PushNotificationUser;
use App\Traits\SendPushNotification;
use Illuminate\Support\Facades\Log;

class NotificationController extends ApiController
{

    use SendPushNotification;

    public function broadcastNotification(Request $request)
    {
        $data = $request->all();
        // event(new BroadcastNotificationEvent($data));
        $title = $data['title'];
        $body = $data['description'];
        $imagePath = '';

        PushNotificationUser::chunk(100, function ($pushNotificationUsers) use ($title, $body) {

            foreach ($pushNotificationUsers as $pushNotification) {
                $notificationData = [
                    "to" =>  $pushNotification->device_token,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                        "image" => '',
                    ],
                    "android" => [
                        "notification" => [
                            "image" => '',
                        ]
                    ],
                    "apns" => [
                        "payload" => [
                            "aps" => [
                                "mutable-content" => 1,
                            ]
                        ]
                    ]

                ];

                $this->pushNotification($notificationData);
            }
        });
        return $this->successResponse("Notification sent successfully", $data);
    }
}
