<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    public function __construct(
        protected Messaging $messaging
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Kirim Push Notification
    |--------------------------------------------------------------------------
    |
    | Dipanggil otomatis oleh Laravel ketika sebuah Notification class
    | mencantumkan FcmChannel::class di method via(). Notifiable (User)
    | harus punya method routeNotificationForFcm() yang mengembalikan
    | device token-nya.
    |
    */

    public function send(object $notifiable, Notification $notification): void
    {
        $token = $notifiable->routeNotificationForFcm();

        if (empty($token)) {
            // User belum punya device token terdaftar (belum pernah buka
            // app mobile / belum login di HP) -- skip, jangan error.
            return;
        }

        if (!method_exists($notification, 'toFcm')) {
            return;
        }

        $payload = $notification->toFcm($notifiable);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(
                FirebaseNotification::create(
                    $payload['title'],
                    $payload['body']
                )
            )
            ->withData($payload['data'] ?? []);

        try {

            $this->messaging->send($message);

        } catch (NotFound $exception) {

            // Token sudah tidak valid lagi (app di-uninstall, dll) --
            // bersihkan dari database supaya tidak dicoba terus-menerus.
            $notifiable->forceFill(['fcm_token' => null])->save();

        } catch (InvalidMessage $exception) {

            Log::warning('FCM: gagal kirim push notification.', [
                'user_id' => $notifiable->id,
                'error' => $exception->getMessage(),
            ]);

        }
    }
}