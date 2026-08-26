<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;

class DashboardService
{
    public function index(User $user): array
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $oneMonthAgo = now()->subMonth();

        $totalEmployee = Employee::query()
            ->forCurrentCompany()
            ->active()
            ->count();

        // Historical reference yang masih bisa dihitung dari data yang tersedia:
        // pegawai aktif saat ini yang sudah terdaftar paling lambat 1 bulan lalu.
        // Ini menghindari angka hardcoded dan tetap konsisten dengan database.
        $employeeReference = Employee::query()
            ->forCurrentCompany()
            ->active()
            ->where('created_at', '<=', $oneMonthAgo)
            ->count();

        $attendanceToday = Attendance::query()
            ->forCurrentCompany()
            ->whereDate('attendance_date', $today)
            ->count();

        $attendanceYesterday = Attendance::query()
            ->forCurrentCompany()
            ->whereDate('attendance_date', $yesterday)
            ->count();

        $lateToday = Attendance::query()
            ->forCurrentCompany()
            ->whereDate('attendance_date', $today)
            ->where('attendance_status', 'Late')
            ->count();

        $lateYesterday = Attendance::query()
            ->forCurrentCompany()
            ->whereDate('attendance_date', $yesterday)
            ->where('attendance_status', 'Late')
            ->count();

        $activeAssignment = Assignment::query()
            ->forCurrentCompany()
            ->whereIn('status', ['Assigned', 'In Progress'])
            ->count();

        // Rekonstruksi kondisi assignment aktif pada tanggal referensi dari rentang
        // jadwalnya. Status historis tidak disimpan sebagai audit log, jadi rentang
        // start/end adalah sumber data paling dapat diverifikasi untuk pembanding.
        $activeAssignmentReference = Assignment::query()
            ->forCurrentCompany()
            ->whereNotIn('status', ['Draft', 'Cancelled'])
            ->where('start_datetime', '<=', $oneMonthAgo)
            ->where('end_datetime', '>=', $oneMonthAgo)
            ->count();

        return [
            'user' => $user,

            'total_employee' => $totalEmployee,
            'attendance_today' => $attendanceToday,
            'late_today' => $lateToday,
            'active_assignment' => $activeAssignment,

            'stat_changes' => [
                'employee' => $this->percentageChange($totalEmployee, $employeeReference),
                'attendance' => $this->percentageChange($attendanceToday, $attendanceYesterday),
                'late' => $this->percentageChange($lateToday, $lateYesterday),
                'assignment' => $this->percentageChange($activeAssignment, $activeAssignmentReference),
            ],

            'attendance_chart' => $this->trendChart(),
            'recent_attendance' => $this->recentAttendance(),
            'active_assignments' => $this->activeAssignments(),
        ];
    }

    /**
     * Hitung perubahan persentase secara dinamis.
     * Jika nilai pembanding 0, kenaikan dari 0 dianggap +100%;
     * 0 ke 0 tetap 0%.
     */
    protected function percentageChange(int|float $current, int|float $previous): float
    {
        if ((float) $previous === 0.0) {
            return (float) $current === 0.0 ? 0.0 : 100.0;
        }

        return round((($current - $previous) / abs($previous)) * 100, 1);
    }

    /**
     * Trend Chart (Attendance + Assignment Selesai)
     *
     * Builds attendance count AND completed-assignment count per day
     * for the last 7 days (including today) for the currently
     * authenticated user's company. Key tetap 'attendance_chart' untuk
     * backward compatibility (dulu cuma attendance), sekarang ada
     * tambahan 'assignment_data' di array yang sama.
     */
    protected function trendChart(): array
    {
        $days = collect(range(6, 0))->map(function (int $daysAgo) {
            return now()->subDays($daysAgo)->startOfDay();
        });

        $attendanceCounts = Attendance::query()
            ->forCurrentCompany()
            ->whereDate('attendance_date', '>=', $days->first())
            ->whereDate('attendance_date', '<=', $days->last())
            ->selectRaw('attendance_date, COUNT(*) as total')
            ->groupBy('attendance_date')
            ->pluck('total', 'attendance_date')
            ->mapWithKeys(function ($total, $date) {
                return [\Illuminate\Support\Carbon::parse($date)->format('Y-m-d') => $total];
            });

        // Assignment Selesai per hari -- "selesai" = pivot finished_at
        // jatuh di hari itu & status Completed. SENGAJA disamakan
        // definisinya dengan EmployeePerformanceService (tab Performance
        // di Detail Employee) supaya makna "Assignment Selesai" tetap
        // konsisten di seluruh app, bukan cuma di dashboard ini.
        $assignmentCounts = Assignment::query()
            ->forCurrentCompany()
            ->join('assignment_employees', 'assignment_employees.assignment_id', '=', 'assignments.id')
            ->where('assignment_employees.status', 'Completed')
            ->whereDate('assignment_employees.finished_at', '>=', $days->first())
            ->whereDate('assignment_employees.finished_at', '<=', $days->last())
            ->selectRaw('assignment_employees.finished_at as finished_at')
            ->get()
            ->countBy(function ($row) {
                return \Illuminate\Support\Carbon::parse($row->finished_at)->format('Y-m-d');
            });

        $labels = $days->map(fn ($day) => $day->format('D'))->values();

        $data = $days->map(function ($day) use ($attendanceCounts) {
            return (int) ($attendanceCounts[$day->format('Y-m-d')] ?? 0);
        })->values();

        $assignmentData = $days->map(function ($day) use ($assignmentCounts) {
            return (int) ($assignmentCounts[$day->format('Y-m-d')] ?? 0);
        })->values();

        return [
            'labels' => $labels->values()->all(),
            'data' => $data->values()->all(),
            'assignment_data' => $assignmentData->values()->all(),
        ];
    }

    /**
     * Recent Attendance
     *
     * 5 attendance record terbaru (check-in/check-out) di company ini,
     * dipakai widget "Aktivitas Absensi Terbaru" di dashboard.
     */
    protected function recentAttendance()
    {
        return Attendance::query()
            ->forCurrentCompany()
            ->with(['employee:id,full_name,photo', 'office:id,name'])
            ->orderByDesc('attendance_date')
            ->orderByDesc('check_in_time')
            ->limit(5)
            ->get()
            ->map(function (Attendance $attendance) {
                return [
                    'id' => $attendance->id,
                    'employee_name' => $attendance->employee?->full_name,
                    'employee_photo_url' => $attendance->employee?->photo
                        ? secure_file_url($attendance->employee->photo)
                        : null,
                    'office_name' => $attendance->office?->name,
                    'attendance_date' => optional($attendance->attendance_date)->format('Y-m-d'),
                    'check_in_time' => $attendance->check_in_time,
                    'check_out_time' => $attendance->check_out_time,
                    'status' => $attendance->attendance_status,
                ];
            });
    }

    /**
     * Active Assignment
     *
     * 5 assignment yang statusnya masih Assigned/In Progress, dipakai
     * widget "Assignment Aktif" di dashboard -- definisi status SAMA
     * PERSIS dengan yang dipakai hitung stat card 'active_assignment' di
     * atas, supaya jumlah di stat card & isi list-nya selalu konsisten.
     */
    protected function activeAssignments()
    {
        return Assignment::query()
            ->forCurrentCompany()
            ->whereIn('status', ['Assigned', 'In Progress'])
            ->with(['employees:id,full_name'])
            ->orderByDesc('start_datetime')
            ->limit(5)
            ->get()
            ->map(function (Assignment $assignment) {
                return [
                    'id' => $assignment->id,
                    'assignment_number' => $assignment->assignment_number,
                    'title' => $assignment->title,
                    'status' => $assignment->status,
                    'priority' => $assignment->priority,
                    'location_name' => $assignment->location_name,
                    'start_datetime' => optional($assignment->start_datetime)->format('Y-m-d H:i'),
                    'end_datetime' => optional($assignment->end_datetime)->format('Y-m-d H:i'),
                    'employee_names' => $assignment->employees->pluck('full_name')->all(),
                ];
            });
    }
}