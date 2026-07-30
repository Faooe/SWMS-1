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

            'attendance_chart' => $this->attendanceChart(),

            'recent_attendance' => $this->recentAttendance(),

            'active_assignments' => $this->activeAssignments(),

        ];
    }

    /**
     * Attendance Chart
     *
     * Builds attendance count per day for the last 7 days
     * (including today) for the currently authenticated
     * user's company.
     */
    protected function attendanceChart(): array
    {
        $days = collect(range(6, 0))->map(function (int $daysAgo) {
            return now()->subDays($daysAgo)->startOfDay();
        });

        $counts = Attendance::query()
            ->forCurrentCompany()
            ->whereDate('attendance_date', '>=', $days->first())
            ->whereDate('attendance_date', '<=', $days->last())
            ->selectRaw('attendance_date, COUNT(*) as total')
            ->groupBy('attendance_date')
            ->pluck('total', 'attendance_date')
            ->mapWithKeys(function ($total, $date) {
                return [\Illuminate\Support\Carbon::parse($date)->format('Y-m-d') => $total];
            });

        $labels = $days->map(fn ($day) => $day->format('D'))->values();

        $data = $days->map(function ($day) use ($counts) {
            return (int) ($counts[$day->format('Y-m-d')] ?? 0);
        })->values();

        return [
            'labels' => $labels->values()->all(),
            'data' => $data->values()->all(),
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