<?php

namespace App\Services;

use App\Models\AssignmentEmployee;
use Illuminate\Database\Eloquent\Builder;

class EmployeeAssignmentStatistics
{
    public function summarize(int $employeeId): array
    {
        $query = AssignmentEmployee::query()
            ->where('employee_id', $employeeId)
            ->whereHas('assignment', fn ($assignment) => $assignment->where('status', '!=', 'Draft'));

        $notWorked = (clone $query)
            ->whereIn('review_status', ['Expired', 'Not Worked'])
            ->count();

        return [
            'total' => (clone $query)->count(),
            'assigned' => (clone $query)
                ->where('status', 'Assigned')
                ->where($this->hasActiveReviewStatus(...))
                ->count(),
            'progress' => (clone $query)
                ->whereIn('status', ['Accepted', 'In Progress'])
                ->where($this->hasActiveReviewStatus(...))
                ->count(),
            'completed' => (clone $query)
                ->where('status', 'Completed')
                ->where('review_status', 'Approved')
                ->count(),
            'cancelled' => (clone $query)->where('status', 'Rejected')->count(),
            'pending_review' => (clone $query)->where('review_status', 'Pending Review')->count(),
            'needs_revision' => (clone $query)->where('review_status', 'Needs Revision')->count(),
            'approved' => (clone $query)->where('review_status', 'Approved')->count(),
            'expired' => $notWorked,
            'not_worked' => $notWorked,
            'late_revision_count' => (clone $query)->where('is_late_revision', true)->count(),
        ];
    }

    private function hasActiveReviewStatus(Builder $query): void
    {
        $query->whereNull('review_status')
            ->orWhereNotIn('review_status', ['Not Worked', 'Expired']);
    }
}
