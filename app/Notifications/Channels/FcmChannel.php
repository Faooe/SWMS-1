<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use RuntimeException;
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

        self::$messaging = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->createMessaging();

        return self::$messaging;
    }

    public function send(object $notifiable, Notification $notification): void
    {
        $token = $notifiable->routeNotificationForFcm();

        if (empty($token)) {
            Log::warning('FCM: token device kosong, push dilewati.', [
                'user_id' => $notifiable->id ?? null,
                'notification' => $notification::class,
            ]);

            return;
        }

        if (! method_exists($notification, 'toFcm')) {
            return;
        }

        $payload = $notification->toFcm($notifiable);

        try {
            // kreait/firebase-php 8.x mengganti API target lama `withTarget()`
            // menjadi builder khusus target seperti `withToken()`.
            $data = collect($payload['data'] ?? [])
                ->mapWithKeys(static fn ($value, $key) => [(string) $key => (string) ($value ?? '')])
                ->all();

            // NotificationSender menetapkan UUID yang sama untuk seluruh
            // channel sebelum FcmChannel dipanggil. Kirimkan ID ini ke mobile
            // agar saat popup ditekan record database yang tepat dapat
            // langsung ditandai sudah dibaca, bukan hanya membuka halaman.
            if (! empty($notification->id)) {
                $data['notification_id'] = (string) $notification->id;
            }

            $message = CloudMessage::new()
                ->withToken($token)
                ->withNotification(
                    FirebaseNotification::create(
                        (string) ($payload['title'] ?? 'SWMS'),
                        (string) ($payload['body'] ?? '')
                    )
                )
                ->withData($data);

            // FCM notification message biasanya memakai collapse key default
            // berdasarkan nama aplikasi. Saat scheduler membuat puluhan
            // notifikasi auto-absent dalam satu waktu, event dengan judul
            // sama dapat digabung sehingga hanya sebagian popup sampai ke
            // perangkat. Setiap notification database diberi key unik agar
            // event attendance/assignment tidak saling menimpa.
            if (! empty($data['notification_id'])) {
                $message = $message->withAndroidConfig([
                    'collapse_key' => 'swms_'.$data['notification_id'],
                    'priority' => 'high',
                ]);
            }

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
