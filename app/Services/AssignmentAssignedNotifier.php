<?php

namespace App\Services;

use App\Models\AssignmentEmployee;
use App\Notifications\AssignmentAssigned;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AssignmentAssignedNotifier
{
    /**
     * Kirim notifikasi Assignment Baru secara idempotent. Jalur direct
     * Assigned dan Draft -> Assigned memakai mekanisme yang sama.
     */
    public function send(AssignmentEmployee $assignmentEmployee): bool
    {
        $assignmentEmployee->loadMissing(['assignment', 'employee.user']);
        $user = $assignmentEmployee->employee?->user;

        if (! $user) {
            Log::warning('AssignmentAssigned dilewati: employee tidak punya user.', [
                'assignment_id' => $assignmentEmployee->assignment_id,
                'assignment_employee_id' => $assignmentEmployee->id,
                'employee_id' => $assignmentEmployee->employee_id,
            ]);

            return false;
        }

        try {
            $alreadyExists = $user->notifications()
                ->where('type', AssignmentAssigned::class)
                ->get()
                ->contains(fn ($notification) => (int) ($notification->data['assignment_employee_id'] ?? 0) === (int) $assignmentEmployee->id
                );
        } catch (Throwable $exception) {
            Log::error('AssignmentAssigned gagal memeriksa notifikasi existing.', [
                'user_id' => $user->id,
                'assignment_id' => $assignmentEmployee->assignment_id,
                'assignment_employee_id' => $assignmentEmployee->id,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }

        if ($alreadyExists) {
            return false;
        }

        $notification = new AssignmentAssigned($assignmentEmployee);

        /*
         * Database notification disimpan TERLEBIH DAHULU secara eksplisit.
         * Dengan ini kegagalan Firebase/FCM tidak pernah menghilangkan event
         * dari bell/list notifikasi di aplikasi.
         */
        try {
            $user->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => AssignmentAssigned::class,
                'data' => $notification->toArray($user),
                'read_at' => null,
            ]);
        } catch (Throwable $exception) {
            Log::error('AssignmentAssigned gagal menyimpan database notification.', [
                'user_id' => $user->id,
                'assignment_id' => $assignmentEmployee->assignment_id,
                'assignment_employee_id' => $assignmentEmployee->id,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }

        /*
         * Push FCM dipisahkan dari database channel. FCM adalah enhancement:
         * kalau credential/token belum siap, notification database tetap ada.
         */
        try {
            app(FcmChannel::class)->send($user, $notification);
        } catch (Throwable $exception) {
            Log::warning('AssignmentAssigned database tersimpan tetapi FCM gagal.', [
                'user_id' => $user->id,
                'assignment_id' => $assignmentEmployee->assignment_id,
                'assignment_employee_id' => $assignmentEmployee->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return true;
    }

    /**
     * Recovery untuk edge-case serverless/cron: assignment bisa sudah berubah
     * menjadi Assigned tetapi proses notifikasinya gagal/terputus setelah status
     * tersimpan. Cron berikutnya tidak lagi menemukan row Draft tersebut, jadi
     * kita backfill notifikasi yang hilang untuk assignment yang baru jatuh tempo.
     */
    public function reconcileRecentlyAssigned(): int
    {
        $created = 0;

        AssignmentEmployee::query()
            ->with(['assignment', 'employee.user'])
            ->whereHas('assignment', function ($query) {
                $query->whereIn('status', ['Assigned', 'In Progress'])
                    ->where('start_datetime', '<=', now())
                    ->where('start_datetime', '>=', now()->subDay());
            })
            ->get()
            ->each(function (AssignmentEmployee $row) use (&$created) {
                if ($this->send($row)) {
                    $created++;
                }
            });

        if ($created > 0) {
            Log::info('Recovered missing scheduled AssignmentAssigned notifications.', [
                'created_count' => $created,
            ]);
        }

        return $created;
    }
}
