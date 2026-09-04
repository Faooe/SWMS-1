<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\StoredFile;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;

class DailyAssignmentReportService
{
    public function downloadForEmployee(User $user, string $uuid)
    {
        $employee = $user->employee;

        if (!$employee) {
            abort(403);
        }

        $assignment = app(EmployeeAssignmentService::class)->find($user, $uuid);

        if (!$assignment->daily_attendance_enabled) {
            throw ValidationException::withMessages([
                'assignment' => ['Final report hanya tersedia untuk assignment dengan Daily Attendance.'],
            ]);
        }

        $finalDate = $assignment->end_datetime?->copy()->startOfDay();

        if (!$finalDate || today()->lt($finalDate)) {
            throw ValidationException::withMessages([
                'assignment' => ['Final report baru tersedia mulai hari terakhir assignment.'],
            ]);
        }

        $daily = app(AssignmentDailyAttendanceService::class)
            ->forEmployee($assignment, $employee);

        $finalRow = collect($daily['calendar'] ?? [])
            ->firstWhere('date', $finalDate->toDateString());

        if ($finalRow && ($finalRow['required'] ?? false) && !($finalRow['checked_out'] ?? false)) {
            throw ValidationException::withMessages([
                'assignment' => ['Check Out attendance hari terakhir terlebih dahulu sebelum mengunduh final report.'],
            ]);
        }

        $attendances = Attendance::query()
            ->where('assignment_id', $assignment->id)
            ->where('employee_id', $employee->id)
            ->where('attendance_type', 'ASSIGNMENT')
            ->orderBy('attendance_date')
            ->get()
            ->keyBy(fn (Attendance $attendance) => $attendance->attendance_date->toDateString());

        $rows = collect($daily['calendar'] ?? [])->map(function (array $row) use ($attendances) {
            $attendance = $attendances->get($row['date']);

            $row['work_description'] = $attendance?->daily_report_notes;
            $row['photo_data_uris'] = collect($attendance?->daily_report_photos ?? [])
                ->map(fn ($path) => $this->photoDataUri($path))
                ->filter()
                ->values()
                ->all();

            return $row;
        })->all();

        $safeNumber = preg_replace('/[^A-Za-z0-9_-]+/', '-', (string) $assignment->assignment_number);
        $filename = 'daily-assignment-report-' . $safeNumber . '.pdf';

        return Pdf::loadView('reports.assignment-daily-final', [
            'assignment' => $assignment,
            'employee' => $employee,
            'rows' => $rows,
            'summary' => $daily['summary'] ?? [],
        ])->setPaper('a4')->download($filename);
    }

    private function photoDataUri(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $file = StoredFile::query()->where('path', $path)->first();

        if (!$file || !str_starts_with((string) $file->mime_type, 'image/')) {
            return null;
        }

        return 'data:' . $file->mime_type . ';base64,' . $file->content;
    }
}
