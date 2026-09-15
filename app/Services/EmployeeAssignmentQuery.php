<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Support\CaseInsensitiveSearch;
use App\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EmployeeAssignmentQuery
{
    public function findForEmployee(int $employeeId, string $uuid): Assignment
    {
        return Assignment::query()
            ->with([
                'office',
                'creator.employee',
                'employees.currentEmployment.position',
                'employees.currentEmployment.office',
                'logs.user.employee',
                'logs.employee',
                'attachments',
            ])
            ->where('uuid', $uuid)
            ->whereHas('employees', function (Builder $query) use ($employeeId): void {
                $query->where('employees.id', $employeeId);
            })
            ->where('assignments.status', '!=', Assignment::STATUS_DRAFT)
            ->firstOrFail();
    }

    public function paginate(int $employeeId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->baseQuery($employeeId);

        $this->applyFilters($query, $employeeId, $filters);
        EmployeeAssignmentOrdering::apply($query, $employeeId);

        $perPage = Pagination::normalize($filters['per_page'] ?? null);

        return $query->paginate($perPage);
    }

    /**
     * Apply request filters independently from pagination so the rules remain
     * reusable and can be regression-tested without loading presentation data.
     */
    public function applyFilters(Builder $query, int $employeeId, array $filters): Builder
    {
        $this->applySearch($query, $filters['search'] ?? null);
        $this->applyStatus($query, $employeeId, $filters['status'] ?? null);
        $this->applyPriority($query, $filters['priority'] ?? null);
        $this->applyDate($query, $filters['date'] ?? null);

        return $query;
    }

    private function baseQuery(int $employeeId): Builder
    {
        return Assignment::query()
            ->with([
                'office',
                'creator.employee',
                'employees.currentEmployment.position',
                'employees.currentEmployment.office',
                'logs',
            ])
            ->whereHas('employees', function (Builder $query) use ($employeeId): void {
                $query->where('employees.id', $employeeId);
            })
            // Draft remains private to the company until it is published.
            ->where('assignments.status', '!=', Assignment::STATUS_DRAFT);
    }

    private function applySearch(Builder $query, ?string $search): void
    {
        if (blank($search)) {
            return;
        }

        CaseInsensitiveSearch::contains($query, $search, ['assignment_number', 'title', 'location_name']);
    }

    private function applyStatus(Builder $query, int $employeeId, ?string $status): void
    {
        if (blank($status)) {
            return;
        }

        if ($status === Assignment::STATUS_CANCELLED) {
            $query->where(function (Builder $cancelledQuery) use ($employeeId): void {
                $cancelledQuery
                    ->where('assignments.status', Assignment::STATUS_CANCELLED)
                    ->orWhereHas('employees', function (Builder $employeeQuery) use ($employeeId): void {
                        $employeeQuery
                            ->where('employees.id', $employeeId)
                            ->where('assignment_employees.status', AssignmentEmployee::STATUS_REJECTED);
                    });
            });

            return;
        }

        $query->whereHas('employees', function (Builder $employeeQuery) use ($employeeId, $status): void {
            $employeeQuery->where('employees.id', $employeeId);

            match ($status) {
                AssignmentEmployee::STATUS_ASSIGNED => $this->applyAssignedStatus($employeeQuery),
                AssignmentEmployee::STATUS_ACCEPTED => $employeeQuery
                    ->where('assignment_employees.status', AssignmentEmployee::STATUS_ACCEPTED)
                    ->whereNull('assignment_employees.review_status'),
                AssignmentEmployee::STATUS_IN_PROGRESS => $employeeQuery
                    ->where('assignment_employees.status', AssignmentEmployee::STATUS_IN_PROGRESS)
                    ->whereNull('assignment_employees.review_status'),
                AssignmentEmployee::REVIEW_PENDING, AssignmentEmployee::REVIEW_NEEDS_REVISION => $employeeQuery
                    ->where('assignment_employees.review_status', $status),
                'Tidak Dikerjakan', AssignmentEmployee::REVIEW_NOT_WORKED => $employeeQuery
                    ->whereIn('assignment_employees.review_status', AssignmentEmployee::notWorkedReviewStatuses()),
                AssignmentEmployee::STATUS_COMPLETED => $employeeQuery
                    ->where('assignment_employees.status', AssignmentEmployee::STATUS_COMPLETED)
                    ->where('assignment_employees.review_status', AssignmentEmployee::REVIEW_APPROVED),
                default => null,
            };
        });
    }

    private function applyAssignedStatus(Builder $query): void
    {
        $query
            ->whereIn('assignment_employees.status', AssignmentEmployee::activeStatuses())
            ->where(function (Builder $reviewQuery): void {
                $reviewQuery
                    ->whereNull('assignment_employees.review_status')
                    ->orWhereNotIn('assignment_employees.review_status', [
                        AssignmentEmployee::REVIEW_PENDING,
                        AssignmentEmployee::REVIEW_NEEDS_REVISION,
                        AssignmentEmployee::REVIEW_APPROVED,
                        ...AssignmentEmployee::notWorkedReviewStatuses(),
                    ]);
            });
    }

    private function applyPriority(Builder $query, ?string $priority): void
    {
        if (filled($priority)) {
            $query->where('priority', $priority);
        }
    }

    private function applyDate(Builder $query, ?string $date): void
    {
        if (blank($date)) {
            return;
        }

        // Include assignments that overlap the selected date.
        $query
            ->whereDate('start_datetime', '<=', $date)
            ->whereDate('end_datetime', '>=', $date);
    }
}
