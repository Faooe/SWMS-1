<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveRequest;

/** Read-only leave/permission aggregates for employee and company dashboards. */
class LeaveRequestStatistics
{
    public function forEmployee(Employee $employee, ?int $year = null): array
    {
        $year ??= now()->year;

        $base = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->whereYear('created_at', $year);

        return [
            'year' => $year,
            'total' => (clone $base)->count(),
            'pending' => (clone $base)->where('status', 'Pending')->count(),
            'approved' => (clone $base)->where('status', 'Approved')->count(),
            'rejected' => (clone $base)->where('status', 'Rejected')->count(),
        ];
    }

    public function forCompany(?int $companyId = null): array
    {
        $companyId ??= auth()->user()?->company_id;

        $base = LeaveRequest::query();
        if ($companyId) {
            $base->where('company_id', $companyId);
        } else {
            $base->forCurrentCompany();
        }

        return [
            'total' => (clone $base)->count(),
            'pending' => (clone $base)->where('status', 'Pending')->count(),
            'approved' => (clone $base)->where('status', 'Approved')->count(),
            'rejected' => (clone $base)->where('status', 'Rejected')->count(),
            'active_today' => (clone $base)
                ->where('status', 'Approved')
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->count(),
        ];
    }
}
