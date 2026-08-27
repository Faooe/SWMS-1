<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory;
use RuntimeException;
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

    private static ?Messaging $messaging = null;

    private function messaging(): Messaging
    {
        if (self::$messaging instanceof Messaging) {
            return self::$messaging;
        }

        $base64 = config('services.firebase.credentials_base64');
        if (blank($base64)) {
            throw new RuntimeException('FIREBASE_CREDENTIALS_BASE64 belum di-set.');
        }

        $json = base64_decode($base64, true);
        $serviceAccount = $json === false ? null : json_decode($json, true);
        if (! is_array($serviceAccount)) {
            throw new RuntimeException('FIREBASE_CREDENTIALS_BASE64 tidak valid.');
        }

        self::$messaging = (new Factory())
            ->withServiceAccount($serviceAccount)
            ->createMessaging();

        return self::$messaging;
    }

    public function send(object $notifiable, Notification $notification): void
    {
        $token = $notifiable->routeNotificationForFcm();

        if (empty($token) || !method_exists($notification, 'toFcm')) {
            return;
        }

        $payload = $notification->toFcm($notifiable);

        try {
            // kreait/firebase-php 8.x mengganti API target lama `withTarget()`
            // menjadi builder khusus target seperti `withToken()`.
            $data = collect($payload['data'] ?? [])
                ->mapWithKeys(static fn ($value, $key) => [(string) $key => (string) ($value ?? '')])
                ->all();

            $message = CloudMessage::new()
                ->withToken($token)
                ->withNotification(
                    FirebaseNotification::create(
                        (string) ($payload['title'] ?? 'SWMS'),
                        (string) ($payload['body'] ?? '')
                    )
                )
                ->withData($data);

            $this->messaging()->send($message);
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
