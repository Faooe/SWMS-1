<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Notifikasi
    |--------------------------------------------------------------------------
    |
    | Dipakai oleh dropdown bell di web (polling AJAX) dan layar notifikasi
    | di aplikasi mobile. Secara default menampilkan 20 notifikasi terbaru;
    | mobile dapat meminta lebih banyak item dan memprioritaskan notifikasi
    | yang belum dibaca.
    |
    */

    public function index(Request $request): JsonResponse
    {
        // Web cukup meminta 20 item, sedangkan mobile meminta daftar yang
        // lebih besar agar notifikasi lama yang belum dibaca tidak tertutup
        // oleh notifikasi baru yang sudah dibaca. Batas tetap dijaga supaya
        // endpoint tidak menarik seluruh tabel notifications sekaligus.
        $perPage = min(max((int) $request->input('per_page', 20), 1), 100);
        $query = $request->user()->notifications();

        if ($request->boolean('unread_first')) {
            $query->orderByRaw('CASE WHEN read_at IS NULL THEN 0 ELSE 1 END ASC');
        }

        $notifications = $query
            ->latest()
            ->limit($perPage)
            ->get()
            ->map(function ($notification) {

                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? null,
                    'title' => $notification->data['title'] ?? null,
                    'message' => $notification->data['message'] ?? null,
                    'url' => $notification->data['url'] ?? null,
                    'assignment_id' => $notification->data['assignment_id'] ?? null,
                    'assignment_uuid' => $notification->data['assignment_uuid'] ?? null,
                    'assignment_employee_id' => $notification->data['assignment_employee_id'] ?? null,
                    'employee_id' => $notification->data['employee_id'] ?? null,
                    'attendance_id' => $notification->data['attendance_id'] ?? null,
                    'leave_request_id' => $notification->data['leave_request_id'] ?? null,
                    'company_id' => $notification->data['company_id'] ?? null,
                    'is_read' => ! is_null($notification->read_at),
                    'created_at' => $notification->created_at,
                ];

            });

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $request->user()->unreadNotifications()->count(),
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

        if (! $notification) {

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
