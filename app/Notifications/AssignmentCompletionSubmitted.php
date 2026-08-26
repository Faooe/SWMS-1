<?php

namespace App\Notifications;

use App\Models\AssignmentEmployee;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke SEMUA admin company begitu employee submit foto bukti +
 * catatan hasil kerja assignment (baik submit pertama kali maupun
 * resubmit setelah revisi) -- supaya company tahu ada laporan yang
 * menunggu di-approve/reject, tanpa perlu bolak-balik cek manual.
 *
 * TIDAK dikirim kalau assignment_auto_approve company aktif, karena
 * saat itu tidak ada tindakan apa pun yang perlu company lakukan --
 * lihat pengecekan $autoApprove di EmployeeAssignmentService::complete()
 * sebelum notifikasi ini di-dispatch.
 */
class AssignmentCompletionSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        protected AssignmentEmployee $assignmentEmployee,
        protected bool $isResubmission = false
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Channel yang Dipakai
    |--------------------------------------------------------------------------
    | Simpan ke database untuk bell/badge DAN kirim push ke HP lewat FCM.
    | FcmChannel dibuat fail-safe: kalau Firebase belum siap, database
    | notification tetap tersimpan dan submit assignment tidak ikut gagal.
    */

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    /*
    |--------------------------------------------------------------------------
    | Data untuk Bell/Badge (Database Channel)
    |--------------------------------------------------------------------------
    */

    public function toArray(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;

        $employee = $this->assignmentEmployee->employee;

        return [

            'type' => 'assignment_completion_submitted',

            'title' => $this->isResubmission
                ? 'Revisi Assignment Disubmit'
                : 'Assignment Selesai Dikerjakan',

            'message' => sprintf(
                '%s %s hasil kerja untuk assignment "%s". Perlu direview & di-approve.',
                $employee?->full_name ?? 'Employee',
                $this->isResubmission ? 'mengirim ulang' : 'menyelesaikan',
                $assignment?->title ?? '-',
            ),

            'assignment_id' => $assignment?->id,

            'assignment_employee_id' => $this->assignmentEmployee->id,

            'employee_id' => $this->assignmentEmployee->employee_id,

            'employee_name' => $employee?->full_name,

            'url' => $assignment
                ? route('assignments.show', $assignment->id)
                : route('assignments.index'),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Data untuk Push Notification (FCM Channel)
    |--------------------------------------------------------------------------
    */

    public function toFcm(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;

        $employee = $this->assignmentEmployee->employee;

        return [

            'title' => $this->isResubmission
                ? 'Revisi Assignment Disubmit'
                : 'Assignment Selesai Dikerjakan',

            'body' => sprintf(
                '%s %s hasil kerja untuk "%s".',
                $employee?->full_name ?? 'Employee',
                $this->isResubmission ? 'mengirim ulang' : 'menyelesaikan',
                $assignment?->title ?? '-',
            ),

            'data' => [
                'type' => 'assignment_completion_submitted',
                'assignment_id' => (string) ($assignment?->id ?? ''),
                'assignment_employee_id' => (string) $this->assignmentEmployee->id,
            ],

        ];
    }
}
