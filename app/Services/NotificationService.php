<?php

namespace App\Services;

use App\Models\UserDevice;

class NotificationService
{
    public static function sendNotification(array $formData)
    {
        try {
            $firebaseToken = UserDevice::where('user_id', auth()->id())->pluck('device_token')->all();

            $SERVER_API_KEY = env('FIRE_BASE_SERVER_KEY');

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => $formData['title'],
                    "body" => $formData['body'],
                    "image" => $formData['image'] ?? 'https://res.cloudinary.com/tad-curtis/image/upload/v1650543360/studio-162614a9eaf8a2.jpg',
                ]
            ];

            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
        }
    }
}
