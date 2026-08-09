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
        return [

            'user' => $user,

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'total_employee' => Employee::query()

                ->forCurrentCompany()

                ->active()

                ->count(),

            'attendance_today' => Attendance::query()

                ->forCurrentCompany()

                ->today()

                ->count(),

            'late_today' => Attendance::query()

                ->forCurrentCompany()

                ->today()

                ->where('attendance_status', 'Late')

                ->count(),

            'active_assignment' => Assignment::query()

                ->forCurrentCompany()

                ->whereIn('status', ['Assigned', 'In Progress'])

                ->count(),

            /*
            |--------------------------------------------------------------------------
            | Dashboard Widgets
            |--------------------------------------------------------------------------
            */

            'attendance_chart' => $this->trendChart(),

            'recent_attendance' => $this->recentAttendance(),

            'active_assignments' => $this->activeAssignments(),

        ];
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
     */
    protected function recentAttendance()
    {
        return collect();
    }

    /**
     * Active Assignment
     */
    protected function activeAssignments()
    {
        return collect();
    }
}