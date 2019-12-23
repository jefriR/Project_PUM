<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function sendNotif($token, $msg){
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'body' => $msg,
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $notification];

        $fcmNotification = [
            'to'        => $token,
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=AIzaSyAYV2oMhv70NtP6im_XvARVRQMCxr8fU1o ',
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
