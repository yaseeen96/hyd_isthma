<?php


namespace App\Helpers;

use Exception;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;

class PushNotificationHelper {

    public static function sendNotification($tokens, $title, $message, $image_url = null, $youtube_url = null) {
        $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
        $messaging = $factory->createMessaging();
        if(isset($tokens)) {
            $imageUrl = 'https://picsum.photos/400/200';
            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $message,
                'image' => $imageUrl,
            ]);
            // Create a CloudMessage
            $message = CloudMessage::new()
            ->withNotification($notification);
            try {
                $result = $messaging->sendMulticast($message, $tokens);
                dd($result);
            } catch(MessagingException $e) {
                throw new Exception($e);
            }
        } else {
            return false;
        }
    }
}