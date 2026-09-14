<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;

/** Company-level assignment metrics, counted by unique assignment records. */
class CompanyAssignmentStatistics
{
    public function build(): array
    {
        $base = Assignment::query()->forCurrentCompany();

        $needsRevision = (clone $base)
            ->whereHas('assignmentEmployees', fn ($q) => $q->where('review_status', AssignmentEmployee::REVIEW_NEEDS_REVISION))
            ->count();

        $pendingReview = (clone $base)
            ->whereHas('assignmentEmployees', fn ($q) => $q->where('review_status', AssignmentEmployee::REVIEW_PENDING))
            ->whereDoesntHave('assignmentEmployees', fn ($q) => $q->where('review_status', AssignmentEmployee::REVIEW_NEEDS_REVISION))
            ->count();

        $active = (clone $base)
            ->whereIn('status', [AssignmentEmployee::STATUS_ASSIGNED, AssignmentEmployee::STATUS_IN_PROGRESS])
            ->where(function ($deadline): void {
                $deadline->where(function ($normal): void {
                    $normal->where('daily_attendance_enabled', false)
                        ->where('end_datetime', '>=', now());
                })->orWhere(function ($daily): void {
                    $daily->where('daily_attendance_enabled', true)
                        ->whereDate('end_datetime', '>=', today());
                });
            })
            ->whereHas('assignmentEmployees', fn ($q) => $q
                ->whereNull('review_status')
                ->whereIn('status', AssignmentEmployee::activeStatuses()))
            ->count();

        $completed = (clone $base)
            ->where('status', Assignment::STATUS_COMPLETED)
            ->whereHas('assignmentEmployees', fn ($q) => $q->where('review_status', AssignmentEmployee::REVIEW_APPROVED))
            ->whereDoesntHave('assignmentEmployees', fn ($q) => $q->whereIn('review_status', [AssignmentEmployee::REVIEW_PENDING, AssignmentEmployee::REVIEW_NEEDS_REVISION]))
            ->count();

        $rejected = (clone $base)
            ->whereHas('assignmentEmployees', fn ($q) => $q->where('status', AssignmentEmployee::STATUS_REJECTED))
            ->whereDoesntHave('assignmentEmployees', fn ($q) => $q->where('status', '!=', AssignmentEmployee::STATUS_REJECTED))
            ->count();

        return [
            'total' => (clone $base)->count(),
            'draft' => (clone $base)->where('status', Assignment::STATUS_DRAFT)->count(),
            'active' => $active,
            'pending_review' => $pendingReview,
            'needs_revision' => $needsRevision,
            'completed' => $completed,
            'rejected' => $rejected,
            'cancelled' => (clone $base)->where('status', Assignment::STATUS_CANCELLED)->count(),
        ];
    }
}
