<?php

namespace App\Services;

use App\Models\Assignment;

/** Company-level assignment metrics, counted by unique assignment records. */
class CompanyAssignmentStatistics
{
    public function build(): array
    {
        $base = Assignment::query()->forCurrentCompany();

        $needsRevision = (clone $base)
            ->whereHas('assignmentEmployees', fn ($q) => $q->where('review_status', 'Needs Revision'))
            ->count();

        $pendingReview = (clone $base)
            ->whereHas('assignmentEmployees', fn ($q) => $q->where('review_status', 'Pending Review'))
            ->whereDoesntHave('assignmentEmployees', fn ($q) => $q->where('review_status', 'Needs Revision'))
            ->count();

        $active = (clone $base)
            ->whereIn('status', ['Assigned', 'In Progress'])
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
                ->whereIn('status', ['Assigned', 'Accepted', 'In Progress']))
            ->count();

        $completed = (clone $base)
            ->where('status', 'Completed')
            ->whereHas('assignmentEmployees', fn ($q) => $q->where('review_status', 'Approved'))
            ->whereDoesntHave('assignmentEmployees', fn ($q) => $q->whereIn('review_status', ['Pending Review', 'Needs Revision']))
            ->count();

        $rejected = (clone $base)
            ->whereHas('assignmentEmployees', fn ($q) => $q->where('status', 'Rejected'))
            ->whereDoesntHave('assignmentEmployees', fn ($q) => $q->where('status', '!=', 'Rejected'))
            ->count();

        return [
            'total' => (clone $base)->count(),
            'draft' => (clone $base)->where('status', 'Draft')->count(),
            'active' => $active,
            'pending_review' => $pendingReview,
            'needs_revision' => $needsRevision,
            'completed' => $completed,
            'rejected' => $rejected,
            'cancelled' => (clone $base)->where('status', 'Cancelled')->count(),
        ];
    }
}
