<?php

namespace App\Services;

use App\Models\Assignment;
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
            ->where('assignments.status', '!=', 'Draft')
            ->firstOrFail();
    }

    public function paginate(int $employeeId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->baseQuery($employeeId);

        $this->applyFilters($query, $employeeId, $filters);
        EmployeeAssignmentOrdering::apply($query, $employeeId);

        return $query->paginate($filters['per_page'] ?? 10);
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
            ->where('assignments.status', '!=', 'Draft');
    }

    private function applySearch(Builder $query, ?string $search): void
    {
        if (blank($search)) {
            return;
        }

        $query->where(function (Builder $searchQuery) use ($search): void {
            $pattern = "%{$search}%";

            $searchQuery
                ->where('assignment_number', 'ILIKE', $pattern)
                ->orWhere('title', 'ILIKE', $pattern)
                ->orWhere('location_name', 'ILIKE', $pattern);
        });
    }

    private function applyStatus(Builder $query, int $employeeId, ?string $status): void
    {
        if (blank($status)) {
            return;
        }

        if ($status === 'Cancelled') {
            $query->where(function (Builder $cancelledQuery) use ($employeeId): void {
                $cancelledQuery
                    ->where('assignments.status', 'Cancelled')
                    ->orWhereHas('employees', function (Builder $employeeQuery) use ($employeeId): void {
                        $employeeQuery
                            ->where('employees.id', $employeeId)
                            ->where('assignment_employees.status', 'Rejected');
                    });
            });

            return;
        }

        $query->whereHas('employees', function (Builder $employeeQuery) use ($employeeId, $status): void {
            $employeeQuery->where('employees.id', $employeeId);

            match ($status) {
                'Assigned' => $this->applyAssignedStatus($employeeQuery),
                'Accepted' => $employeeQuery
                    ->where('assignment_employees.status', 'Accepted')
                    ->whereNull('assignment_employees.review_status'),
                'In Progress' => $employeeQuery
                    ->where('assignment_employees.status', 'In Progress')
                    ->whereNull('assignment_employees.review_status'),
                'Pending Review', 'Needs Revision' => $employeeQuery
                    ->where('assignment_employees.review_status', $status),
                'Tidak Dikerjakan', 'Not Worked' => $employeeQuery
                    ->whereIn('assignment_employees.review_status', ['Not Worked', 'Expired']),
                'Completed' => $employeeQuery
                    ->where('assignment_employees.status', 'Completed')
                    ->where('assignment_employees.review_status', 'Approved'),
                default => null,
            };
        });
    }

    private function applyAssignedStatus(Builder $query): void
    {
        $query
            ->whereIn('assignment_employees.status', ['Assigned', 'Accepted', 'In Progress'])
            ->where(function (Builder $reviewQuery): void {
                $reviewQuery
                    ->whereNull('assignment_employees.review_status')
                    ->orWhereNotIn('assignment_employees.review_status', [
                        'Pending Review',
                        'Needs Revision',
                        'Approved',
                        'Not Worked',
                        'Expired',
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
