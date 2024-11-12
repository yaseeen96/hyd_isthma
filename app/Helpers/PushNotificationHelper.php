<?php
namespace App\Helpers;

use App\Jobs\PushNotificationJob;
use Kreait\Firebase\Factory;

class PushNotificationHelper {
    public static function sendNotification($data) {
        $tokens = $data['tokens'];
        $title = $data['title'];
        $message = $data['message'];
        $notificationUrl = env('JIH_APP_URL') . 'notification?id=' . $data['id'];
        $imgUrl = !empty($data['imgUrl']) ? $data['imgUrl'] : env('APP_URL') . 'assets/img/no-image.png';

        $factory = (new Factory)->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')));
        $messaging = $factory->createMessaging();

        // Split tokens into chunks of 1000
        $tokenChunks = array_chunk($tokens, 1000);
        $allValidTokens = [];

        foreach ($tokenChunks as $tokenChunk) {
            // Validate tokens
            $filteredTokens = $messaging->validateRegistrationTokens($tokenChunk);
            $validTokens = $filteredTokens['valid'];

            // Collect valid tokens for immediate return
            $allValidTokens = array_merge($allValidTokens, $validTokens);

            // Dispatch job to send notifications asynchronously
            PushNotificationJob::dispatch($title, $message, $notificationUrl, $imgUrl, $validTokens);
        }
        return [
            'valid_tokens' => $allValidTokens,
            'unknown_tokens' => $filteredTokens['unknown'] ?? [],
            'invalid_tokens' => $filteredTokens['invalid'] ?? []
        ];
    }
}