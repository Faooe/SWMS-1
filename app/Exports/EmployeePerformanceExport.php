<?php

namespace App\Exports;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EmployeePerformanceExport
{
    public function __construct(
        private Employee $employee,
        private Carbon $from,
        private Carbon $to,
        private array $monthlyChart,
        private array $summary,
        private Collection $attendanceDetail,
        private Collection $assignmentDetail,
        private array $reviewSummary = [],
    ) {
    }

    public function title(): string
    {
        if ($this->from->isSameDay($this->to)) {
            return $this->from->translatedFormat('d F Y');
        }

        if ($this->from->isSameMonth($this->to)) {
            return $this->from->translatedFormat('F Y');
        }

        return $this->from->translatedFormat('d M Y').' - '.$this->to->translatedFormat('d M Y');
    }

    public function filenameSlug(): string
    {
        return $this->from->format('Y-m-d').'_'.$this->to->format('Y-m-d');
    }

    /*
    |--------------------------------------------------------------------------
    | Getter (dipakai controller untuk isi view PDF)
    |--------------------------------------------------------------------------
    */

    public function monthlyChart(): array
    {
        return $this->monthlyChart;
    }

    public function summary(): array
    {
        return $this->summary;
    }

    public function reviewSummary(): array
    {
        return $this->reviewSummary;
    }

    public function attendanceDetail(): Collection
    {
        return $this->attendanceDetail;
    }

    public function assignmentDetail(): Collection
    {
        return $this->assignmentDetail;
    }

    /*
    |--------------------------------------------------------------------------
    | Sheet: Ringkasan per Bulan
    |--------------------------------------------------------------------------
    */

    public function summaryHeadings(): array
    {
        return [
            'Periode',
            'Total Attendance',
            'Hadir (Present)',
            'Terlambat (Late)',
            'Assignment Selesai',
        ];
    }

    public function summaryRows(): array
    {
        $rows = collect($this->monthlyChart)
            ->map(fn (array $row) => [
                $row['label'],
                $row['attendance_total'],
                $row['attendance_present'],
                $row['attendance_late'],
                $row['assignment_completed'],
            ])
            ->all();

        $rows[] = [
            'TOTAL',
            $this->summary['attendance_total'],
            $this->summary['attendance_present'],
            $this->summary['attendance_late'],
            $this->summary['assignment_completed'],
        ];

        // Baris kosong pemisah, lalu breakdown hasil review assignment
        // (Approved/Pending Review/Needs Revision/Expired/Late) --
        // kolom ke-2 dipakai untuk angkanya, kolom sisanya dikosongkan.
        if (!empty($this->reviewSummary)) {

            $rows[] = ['', '', '', '', ''];
            $rows[] = ['RINGKASAN REVIEW ASSIGNMENT', '', '', '', ''];
            $rows[] = ['Approved', $this->reviewSummary['approved'] ?? 0, '', '', ''];
            $rows[] = ['Pending Review', $this->reviewSummary['pending_review'] ?? 0, '', '', ''];
            $rows[] = ['Needs Revision', $this->reviewSummary['needs_revision'] ?? 0, '', '', ''];
            $rows[] = ['Expired (Tidak Terselesaikan)', $this->reviewSummary['expired'] ?? 0, '', '', ''];
            $rows[] = ['Late Pengerjaan (Revisi Telat)', $this->reviewSummary['late_revision_count'] ?? 0, '', '', ''];
            $rows[] = ['Rejected Assignment', $this->reviewSummary['rejected'] ?? 0, '', '', ''];

        }

        return $rows;
    }

    public function summaryStyles(): array
    {
        $styles = [];
        foreach ($this->monthlyChart as $row) {
            $styles[] = ['blue', 'blue', 'green', 'amber', 'green'];
        }
        $styles[] = ['total', 'total', 'total', 'total', 'total'];
        if (!empty($this->reviewSummary)) {
            $styles[] = array_fill(0, 5, 'normal');
            $styles[] = array_fill(0, 5, 'blue');
            foreach (['approved', 'pending_review', 'needs_revision', 'expired', 'late_revision_count', 'rejected'] as $key) {
                $styles[] = ['blue', match ($key) {
                    'approved' => 'green',
                    'pending_review', 'expired', 'late_revision_count' => 'amber',
                    'needs_revision' => 'purple',
                    'rejected' => 'red',
                    default => 'normal',
                }, 'normal', 'normal', 'normal'];
            }
        }

        return $styles;
    }

    /*
    |--------------------------------------------------------------------------
    | Sheet: Detail Attendance
    |--------------------------------------------------------------------------
    */

    public function attendanceHeadings(): array
    {
        return [
            'Tanggal',
            'Check In',
            'Check Out',
            'Office',
            'Status',
            'Terlambat (menit)',
        ];
    }

    public function attendanceRows(): array
    {
        return $this->attendanceDetail
            ->map(fn (Attendance $attendance) => [
                $attendance->attendance_date->format('d/m/Y'),
                $attendance->check_in_time ?? '-',
                $attendance->check_out_time ?? '-',
                $attendance->office->name ?? '-',
                $attendance->attendance_status,
                $attendance->late_minutes ?? 0,
            ])
            ->all();
    }

    public function attendanceStyles(): array
    {
        return $this->attendanceDetail->map(function (Attendance $attendance): array {
            return [
                'normal', 'normal', 'normal', 'normal',
                $this->attendanceStatusStyle((string) $attendance->attendance_status),
                ((int) ($attendance->late_minutes ?? 0)) > 0 ? 'amber' : 'muted',
            ];
        })->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Sheet: Detail Assignment Selesai
    |--------------------------------------------------------------------------
    */

    public function assignmentHeadings(): array
    {
        return [
            'No. Assignment',
            'Judul',
            'Tipe',
            'Lokasi',
            'Ditugaskan Pada',
            'Selesai Pada',
            'Status Pengerjaan',
            'Status Review',
            'Late Pengerjaan',
            'Jumlah Revisi',
        ];
    }

    public function assignmentRows(): array
    {
        return $this->assignmentDetail
            ->map(fn (Assignment $assignment) => [
                $assignment->assignment_number,
                $assignment->title,
                $assignment->assignment_type,
                $assignment->location_name ?? '-',
                optional($assignment->pivot->assigned_at)->format('d/m/Y H:i') ?? '-',
                optional($assignment->pivot->finished_at)->format('d/m/Y H:i') ?? '-',
                $assignment->pivot->status ?? '-',
                $assignment->pivot->review_status ?? '-',
                $assignment->pivot->is_late_revision ? 'Ya' : 'Tidak',
                $assignment->pivot->revision_count ?? 0,
            ])
            ->all();
    }

    public function assignmentStyles(): array
    {
        return $this->assignmentDetail->map(function (Assignment $assignment): array {
            return [
                'normal', 'normal', 'normal', 'normal', 'normal', 'normal',
                $this->assignmentStatusStyle((string) ($assignment->pivot->status ?? '')),
                $this->reviewStyle((string) ($assignment->pivot->review_status ?? '')),
                $assignment->pivot->is_late_revision ? 'amber' : 'muted',
                'blue',
            ];
        })->all();
    }

    private function attendanceStatusStyle(string $status): string
    {
        return match (strtolower(trim($status))) {
            'present', 'hadir' => 'green',
            'late', 'telat', 'permission', 'izin' => 'amber',
            'leave', 'cuti' => 'purple',
            'absent', 'absen' => 'red',
            default => 'muted',
        };
    }

    private function assignmentStatusStyle(string $status): string
    {
        return match (strtolower(trim($status))) {
            'completed', 'selesai' => 'green',
            'rejected', 'ditolak' => 'red',
            'assigned', 'accepted', 'in progress', 'not worked', 'expired' => 'amber',
            default => 'muted',
        };
    }

    private function reviewStyle(string $status): string
    {
        return match (strtolower(trim($status))) {
            'approved', 'disetujui' => 'green',
            'needs revision', 'revisi' => 'purple',
            'pending review', 'menunggu review' => 'amber',
            'rejected', 'ditolak' => 'red',
            default => 'muted',
        };
    }
}
