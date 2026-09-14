<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use Illuminate\Database\Eloquent\Builder;

class EmployeeAssignmentStatistics
{
    public function summarize(int $employeeId): array
    {
        $query = AssignmentEmployee::query()
            ->where('employee_id', $employeeId)
            ->whereHas('assignment', fn ($assignment) => $assignment->where('status', '!=', Assignment::STATUS_DRAFT));

        $notWorked = (clone $query)
            ->whereIn('review_status', [AssignmentEmployee::REVIEW_EXPIRED, AssignmentEmployee::REVIEW_NOT_WORKED])
            ->count();

        return [
            'total' => (clone $query)->count(),
            'assigned' => (clone $query)
                ->where('status', AssignmentEmployee::STATUS_ASSIGNED)
                ->where($this->hasActiveReviewStatus(...))
                ->count(),
            'progress' => (clone $query)
                ->whereIn('status', [AssignmentEmployee::STATUS_ACCEPTED, AssignmentEmployee::STATUS_IN_PROGRESS])
                ->where($this->hasActiveReviewStatus(...))
                ->count(),
            'completed' => (clone $query)
                ->where('status', AssignmentEmployee::STATUS_COMPLETED)
                ->where('review_status', AssignmentEmployee::REVIEW_APPROVED)
                ->count(),
            'cancelled' => (clone $query)->where('status', AssignmentEmployee::STATUS_REJECTED)->count(),
            'pending_review' => (clone $query)->where('review_status', AssignmentEmployee::REVIEW_PENDING)->count(),
            'needs_revision' => (clone $query)->where('review_status', AssignmentEmployee::REVIEW_NEEDS_REVISION)->count(),
            'approved' => (clone $query)->where('review_status', AssignmentEmployee::REVIEW_APPROVED)->count(),
            'expired' => $notWorked,
            'not_worked' => $notWorked,
            'late_revision_count' => (clone $query)->where('is_late_revision', true)->count(),
        ];
    }

    private function hasActiveReviewStatus(Builder $query): void
    {
        $query->whereNull('review_status')
            ->orWhereNotIn('review_status', [AssignmentEmployee::REVIEW_NOT_WORKED, AssignmentEmployee::REVIEW_EXPIRED]);
    }
}
