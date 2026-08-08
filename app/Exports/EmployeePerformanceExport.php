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
    ) {
    }

    public function title(): string
    {
        return $this->from->isSameMonth($this->to)
            ? $this->from->translatedFormat('F Y')
            : $this->from->translatedFormat('M Y').' - '.$this->to->translatedFormat('M Y');
    }

    public function filenameSlug(): string
    {
        return $this->from->format('Y-m').'_'.$this->to->format('Y-m');
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
            'Bulan',
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

        return $rows;
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
            'Selesai Pada',
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
                optional($assignment->pivot->finished_at)->format('d/m/Y H:i') ?? '-',
            ])
            ->all();
    }
}
