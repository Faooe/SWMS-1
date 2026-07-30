<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Collection;

class AttendanceExport
{
    public function __construct(
        private Collection $attendances,
        private int $year,
        private int $month,
    ) {
    }

    public function title(): string
    {
        return \Carbon\Carbon::create($this->year, $this->month, 1)
            ->translatedFormat('F Y');
    }

    public function headings(): array
    {
        return [
            'Employee Number',
            'Employee Name',
            'Office',
            'Date',
            'Check In',
            'Check Out',
            'Status',
            'Late (minutes)',
        ];
    }

    public function rows(): array
    {
        return $this->attendances
            ->map(fn (Attendance $attendance) => $this->map($attendance))
            ->all();
    }

    private function map(Attendance $attendance): array
    {
        return [
            $attendance->employee->employee_number ?? '-',
            $attendance->employee->full_name ?? '-',
            optional($attendance->employee->currentEmployment)->office?->name ?? '-',
            $attendance->attendance_date->format('d/m/Y'),
            $attendance->check_in_time ?? '-',
            $attendance->check_out_time ?? '-',
            $attendance->attendance_status,
            $attendance->late_minutes ?? 0,
        ];
    }
}