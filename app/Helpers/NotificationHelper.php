<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Notification;
use Google\Client as GoogleClient;

class NotificationHelper
{
    private static function getAccessToken(): string
    {
        if (!file_exists(storage_path('app/firebase-service-account.json'))) {
            throw new \Exception('Firebase credentials not found');
        }
        
        $client = new GoogleClient();
        $client->setAuthConfig(storage_path('app/firebase-service-account.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        return $client->getAccessToken()['access_token'];
    }

    public static function send($userId, $title, $message, $type = 'general')
    {
        $user = User::find($userId);
        if (!$user) return;

        // ⬇️ HAPUS Notification::create di sini, sudah ditangani Notification::send()

        if (!$user->fcm_token) return;

        try {
            $serviceAccountPath = storage_path('app/firebase-service-account.json');
            
            if (!file_exists($serviceAccountPath)) {
                \Log::warning('FCM Skipped: firebase-service-account.json not found.');
                return;
            }

            $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
            $projectId = $serviceAccount['project_id'];

            $accessToken = self::getAccessToken();

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ])->post($url, [
                'message' => [
                    'token' => $user->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $message,
                    ],
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'channel_id' => 'high_importance_channel',
                            'sound'      => 'default',
                        ],
                    ],
                    'data' => [
                        'title' => $title,
                        'body'  => $message,
                        'type'  => $type,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
                ]
            ]);

            \Log::info('FCM v1 RESPONSE', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (\Exception $e) {
            \Log::error('FCM Error: ' . $e->getMessage());
        }
    }
}