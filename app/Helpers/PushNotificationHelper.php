<?php


namespace App\Helpers;

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
            // Default notificaiton
            $notification = Notification::create($title, $message)->withImageUrl($imgUrl);
            // Android push notification configurations
            // preparing message
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData(['url' => $notificationUrl]);
            try {
                // sending push notificaiton message
                $result = $messaging->sendMulticast($message, $tokens);
                // dd($result);
                return true;
            } catch(MessagingException $e) {
                throw new Exception($e);
            }
        } else {
            return false;
        }
    }
}