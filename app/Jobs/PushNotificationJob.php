<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;
use Illuminate\Support\Facades\Log;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
     protected $title, $message, $notificationUrl, $imgUrl, $tokens;
    /**
     * Create a new job instance.
     */
    public function __construct($title, $message, $notificationUrl, $imgUrl, $tokens) {
        $this->title = $title;
        $this->message = $message;
        $this->notificationUrl = $notificationUrl;
        $this->imgUrl = $imgUrl;
        $this->tokens = $tokens;
    }

    /**
     * Execute the job.
     */
    public function handle() {
        $factory = (new Factory)->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')));
        $messaging = $factory->createMessaging();

        // Prepare notification
        $notification = Notification::create($this->title, $this->message)->withImageUrl($this->imgUrl);
        $cloudMessage = CloudMessage::new()->withNotification($notification)->withData(['url' => $this->notificationUrl]);

        try {
            // Send notification to the valid tokens
            $messaging->sendMulticast($cloudMessage, $this->tokens);
        } catch (MessagingException $e) {
            // Log messaging exceptions
            Log::error('Firebase Messaging Exception: ' . $e->getMessage());
        }
    }
}