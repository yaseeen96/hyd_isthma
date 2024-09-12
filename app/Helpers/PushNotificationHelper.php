<?php
namespace App\Helpers;

use App\Models\Notification as ModelsNotification;
use Exception;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;

class PushNotificationHelper {
    public static function sendNotification($data) {
        $notificationUrl = env('JIH_APP_URL') . 'notification?id='.$data['id'];
        $tokens = $data['tokens'];
        $title = $data['title'];
        $message = $data['message'];
        $ytUrl = $data['ytUrl'];
        $imgUrl = !empty($data['imgUrl']) ? $data['imgUrl'] : env('APP_URL').'assets/img/no-image.png';
        $factory = (new Factory)->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')),);
        $messaging = $factory->createMessaging();
        if(isset($tokens)) {
            $validTokens = [];
            $unkownTokens = [];
            $invalidTokens = [];
            // Split tokens into chunks of 1000
            $tokenChunks = array_chunk($tokens, 1000);
            // Default notificaiton
            $notification = Notification::create($title, $message)->withImageUrl($imgUrl);
            // Android push notification configurations
            // preparing message
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData(['url' => $notificationUrl]);
            foreach ($tokenChunks as $tokenChunk) {
                try {
                    $filteredTokens = $messaging->validateRegistrationTokens($tokenChunk);
                    $validTokens = array_merge($validTokens, $filteredTokens['valid']);
                    $unkownTokens = array_merge($unkownTokens, $filteredTokens['unknown']);
                    $invalidTokens = array_merge($invalidTokens, $filteredTokens['invalid']);
                    // sending push notificaiton message
                    $result = $messaging->sendMulticast($message, $filteredTokens['valid']);
                    // usleep(100000);
                } catch (MessagingException $e) {
                    throw new Exception($e);
                }
            }
            return [
                'valid_tokens' => $validTokens,
                'unknown_tokens' => $unkownTokens,
                'invalid_tokens' => $invalidTokens
            ];
        } else {
            return false;
        }
    }
}
