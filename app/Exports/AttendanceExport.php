<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    ShouldAutoSize,
    WithStyles
{
    public function __construct(
        private Collection $attendances,
        private int $year,
        private int $month,
    ) {
    }

    public function collection(): Collection
    {
        return $this->attendances;
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

    public function map($attendance): array
    {
        /** @var Attendance $attendance */

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

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
