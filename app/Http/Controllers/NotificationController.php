<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Notifikasi
    |--------------------------------------------------------------------------
    |
    | Dipakai oleh dropdown bell di web (polling AJAX) dan layar notifikasi
    | di aplikasi mobile. Menampilkan 20 notifikasi terbaru milik user yang
    | sedang login (admin company / employee, keduanya sama-sama User).
    |
    */

    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->limit(20)
            ->get()
            ->map(function ($notification) {

                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? null,
                    'title' => $notification->data['title'] ?? null,
                    'message' => $notification->data['message'] ?? null,
                    'url' => $notification->data['url'] ?? null,
                    'is_read' => !is_null($notification->read_at),
                    'created_at' => $notification->created_at,
                ];

            });

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Jumlah Notifikasi Belum Dibaca
    |--------------------------------------------------------------------------
    | Dipakai untuk angka merah di icon bell / badge di web & mobile.
    | Sengaja dipisah dari index() supaya bisa di-poll lebih sering
    | (misal tiap 30 detik) tanpa perlu narik semua data notifikasi.
    */

    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Tandai 1 Notifikasi Sudah Dibaca
    |--------------------------------------------------------------------------
    */

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {

            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);

        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Tandai Semua Notifikasi Sudah Dibaca
    |--------------------------------------------------------------------------
    */

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Daftarkan FCM Token (Push Notification)
    |--------------------------------------------------------------------------
    |
    | Dipanggil oleh aplikasi mobile (Flutter) setiap kali berhasil login
    | atau setiap token Firebase-nya di-refresh, supaya server tahu ke
    | "alamat" mana push notification harus dikirim untuk user ini.
    |
    */

    public function storeFcmToken(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update([
            'fcm_token' => $request->input('fcm_token'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token berhasil didaftarkan.',
        ]);
    }
}