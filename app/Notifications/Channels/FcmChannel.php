<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Throwable;

class FcmChannel
{
    /*
    |--------------------------------------------------------------------------
    | Kirim Push Notification
    |--------------------------------------------------------------------------
    |
    | Messaging sengaja di-resolve di dalam send(), bukan lewat constructor.
    | Dengan begitu kalau credential Firebase belum tersedia di suatu
    | environment, database notification tetap berhasil tersimpan dan aksi
    | utama (submit assignment) tidak ikut gagal hanya karena push gagal.
    |
    */

    public function send(object $notifiable, Notification $notification): void
    {
        $token = $notifiable->routeNotificationForFcm();

        if (empty($token) || !method_exists($notification, 'toFcm')) {
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
            app(Messaging::class)->send($message);
        } catch (NotFound $exception) {
            $notifiable->forceFill(['fcm_token' => null])->save();
        } catch (InvalidMessage $exception) {
            Log::warning('FCM: gagal kirim push notification.', [
                'user_id' => $notifiable->id,
                'error' => $exception->getMessage(),
            ]);
        } catch (Throwable $exception) {
            // Push adalah enhancement. Jangan sampai assignment completion
            // menjadi 500 hanya karena Firebase belum siap di environment.
            Log::warning('FCM: channel tidak tersedia, push dilewati.', [
                'user_id' => $notifiable->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
