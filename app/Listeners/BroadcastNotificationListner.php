<?php

namespace App\Listeners;

use App\Events\BroadcastNotificationEvent;
use App\Models\PushNotificationUser;
use App\Traits\SendPushNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BroadcastNotificationListner implements ShouldQueue
{
    use SendPushNotification;

    private $details;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendNotoficationEvent  $event
     * @return void
     */
    public function handle(BroadcastNotificationEvent $event)
    {
        $data = $event->data;
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
    }
}
