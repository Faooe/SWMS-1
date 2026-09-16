<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Support\CaseInsensitiveSearch;
use Illuminate\Database\Eloquent\Builder;

class CompanyAssignmentQuery
{
    public function build(array $filters = []): Builder
    {
        $query = Assignment::query()
            ->forCurrentCompany()
            ->with([
                'office',
                'creator',
            ])
            ->withCount('assignmentEmployees')
            ->withCount([
                'assignmentEmployees as rejected_employee_count' => fn ($q) => $q->where('status', AssignmentEmployee::STATUS_REJECTED),
                'assignmentEmployees as pending_review_employee_count' => fn ($q) => $q->where('review_status', AssignmentEmployee::REVIEW_PENDING),
                'assignmentEmployees as needs_revision_employee_count' => fn ($q) => $q->where('review_status', AssignmentEmployee::REVIEW_NEEDS_REVISION),
                'assignmentEmployees as approved_employee_count' => fn ($q) => $q->where('review_status', AssignmentEmployee::REVIEW_APPROVED),
                'assignmentEmployees as not_worked_employee_count' => fn ($q) => $q->whereIn('review_status', AssignmentEmployee::notWorkedReviewStatuses()),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (! empty($filters['search'])) {
            CaseInsensitiveSearch::contains($query, (string) $filters['search'], ['assignment_number', 'title', 'location_name']);
        }

        /*
        |--------------------------------------------------------------------------
        | Office
        |--------------------------------------------------------------------------
        */

        if (! empty($filters['office'])) {
            $query->where('office_id', $filters['office']);
        }

        /*
        |--------------------------------------------------------------------------
        | Priority
        |--------------------------------------------------------------------------
        */

        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        | Assignment ditampilkan bila jadwalnya bersinggungan dengan tanggal
        | yang dipilih. Ini sama dengan semantik filter My Assignment.
        */
        if (! empty($filters['date'])) {
            $query->whereDate('start_datetime', '<=', $filters['date'])
                ->whereDate('end_datetime', '>=', $filters['date']);
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (! empty($filters['status'])) {
            $status = $filters['status'];

            /*
            |------------------------------------------------------------------
            | Status workflow untuk Company Admin
            |------------------------------------------------------------------
            |
            | Kolom assignments.status tetap dipakai sebagai status internal
            | (Draft/Assigned/In Progress/Completed/Cancelled). Namun UI Company
            | perlu membedakan hasil yang baru disubmit dari hasil yang sudah
            | di-approve. Karena itu Pending Review dan Needs Revision dibaca
            | dari assignment_employees.review_status.
            |
            */
            switch ($status) {
                case Assignment::STATUS_DRAFT:
                    $query->where('assignments.status', Assignment::STATUS_DRAFT);
                    break;

                case 'Active':
                    $query->whereIn('assignments.status', [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS])
                        // "Active" berarti periode assignment memang masih berjalan.
                        // Daily Attendance tetap dianggap berjalan sampai akhir tanggal
                        // terakhir (grace check-out harian), bukan selamanya hanya karena
                        // status global masih In Progress.
                        ->where(function ($deadline) {
                            $deadline->where(function ($normal) {
                                $normal->where('daily_attendance_enabled', false)
                                    ->where('end_datetime', '>=', now());
                            })->orWhere(function ($daily) {
                                $daily->where('daily_attendance_enabled', true)
                                    ->whereDate('end_datetime', '>=', today());
                            });
                        })
                        // Mixed-team assignment tetap aktif selama minimal ada satu
                        // employee yang masih benar-benar berada pada workflow aktif.
                        ->whereHas('assignmentEmployees', function ($employeeQuery) {
                            $employeeQuery->whereNull('review_status')
                                ->whereIn('status', AssignmentEmployee::activeStatuses());
                        });
                    break;

                case Assignment::STATUS_ASSIGNED:
                    $query->where('assignments.status', Assignment::STATUS_ASSIGNED)
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                AssignmentEmployee::REVIEW_PENDING,
                                AssignmentEmployee::REVIEW_NEEDS_REVISION,
                            ]);
                        });
                    break;

                case Assignment::STATUS_IN_PROGRESS:
                    // Tetap dipertahankan untuk Company Admin karena ini status
                    // operasional yang berguna untuk mengetahui assignment yang
                    // benar-benar sedang dikerjakan sebelum disubmit.
                    $query->where('assignments.status', Assignment::STATUS_IN_PROGRESS)
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                AssignmentEmployee::REVIEW_PENDING,
                                AssignmentEmployee::REVIEW_NEEDS_REVISION,
                            ]);
                        });
                    break;

                case AssignmentEmployee::REVIEW_PENDING:
                    $query->whereHas('employees', function ($employeeQuery) {
                        $employeeQuery->where('assignment_employees.review_status', AssignmentEmployee::REVIEW_PENDING);
                    });
                    break;

                case AssignmentEmployee::REVIEW_NEEDS_REVISION:
                    $query->whereHas('employees', function ($employeeQuery) {
                        $employeeQuery->where('assignment_employees.review_status', AssignmentEmployee::REVIEW_NEEDS_REVISION);
                    });
                    break;

                case Assignment::STATUS_COMPLETED:
                    // Assignment global bisa sudah Completed segera setelah semua
                    // employee submit. Di UI Company, Completed baru berarti hasil
                    // sudah di-approve (manual maupun Auto Approve).
                    $query->where('assignments.status', Assignment::STATUS_COMPLETED)
                        ->whereHas('employees', function ($employeeQuery) {
                            $employeeQuery->where('assignment_employees.review_status', AssignmentEmployee::REVIEW_APPROVED);
                        })
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                AssignmentEmployee::REVIEW_PENDING,
                                AssignmentEmployee::REVIEW_NEEDS_REVISION,
                            ]);
                        });
                    break;

                case AssignmentEmployee::STATUS_REJECTED:
                    $query->whereHas('employees', function ($employeeQuery) {
                        $employeeQuery->where('assignment_employees.status', AssignmentEmployee::STATUS_REJECTED);
                    });
                    break;

                case 'Belum Dikerjakan':
                case 'Not Started':
                    // Employee sudah ditugaskan tetapi belum menerima atau
                    // mulai mengerjakan assignment. Hanya tampil sebelum batas
                    // waktu berakhir; setelah itu masuk kategori Tidak Dikerjakan.
                    $query->whereIn('assignments.status', [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS])
                        ->where(function ($deadline) {
                            $deadline->where(function ($normal) {
                                $normal->where('daily_attendance_enabled', false)
                                    ->where('end_datetime', '>=', now());
                            })->orWhere(function ($daily) {
                                $daily->where('daily_attendance_enabled', true)
                                    ->whereDate('end_datetime', '>=', today());
                            });
                        })
                        ->whereHas('employees', function ($employeeQuery) {
                            $employeeQuery->whereNull('assignment_employees.review_status')
                                ->whereIn('assignment_employees.status', [
                                    AssignmentEmployee::STATUS_ASSIGNED,
                                    AssignmentEmployee::STATUS_ACCEPTED,
                                ]);
                        });
                    break;

                case 'Tidak Dikerjakan':
                case 'Not Worked':
                    // Review status adalah sumber utama. Kondisi deadline
                    // menjadi fallback agar data langsung dapat ditemukan walau
                    // scheduler belum sempat menjalankan auto-expire.
                    $dailyDeadlinePassed = now()->format('H:i:s') >= '23:00:00';

                    $query->where(function ($state) use ($dailyDeadlinePassed) {
                        $state->whereHas('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn(
                                'assignment_employees.review_status',
                                AssignmentEmployee::notWorkedReviewStatuses()
                            );
                        })->orWhere(function ($overdue) use ($dailyDeadlinePassed) {
                            $overdue->whereIn('assignments.status', [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS])
                                ->where(function ($deadline) use ($dailyDeadlinePassed) {
                                    $deadline->where(function ($normal) {
                                        $normal->where('daily_attendance_enabled', false)
                                            ->where('end_datetime', '<', now());
                                    })->orWhere(function ($daily) use ($dailyDeadlinePassed) {
                                        $daily->where('daily_attendance_enabled', true)
                                            ->where(function ($date) use ($dailyDeadlinePassed) {
                                                $date->whereDate('end_datetime', '<', today());
                                                if ($dailyDeadlinePassed) {
                                                    $date->orWhereDate('end_datetime', '=', today());
                                                }
                                            });
                                    });
                                })
                                ->whereHas('employees', function ($employeeQuery) {
                                    $employeeQuery->whereNull('assignment_employees.review_status')
                                        ->whereIn('assignment_employees.status', AssignmentEmployee::activeStatuses());
                                });
                        });
                    });
                    break;

                case Assignment::STATUS_CANCELLED:
                    $query->where('assignments.status', Assignment::STATUS_CANCELLED);
                    break;

                default:
                    // Abaikan nilai filter yang tidak dikenal daripada
                    // memfilter kolom status dengan pseudo-status workflow.
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $filters['sort'] ?? 'start_datetime',
            $filters['direction'] ?? 'desc'
        );

        return $query;
    }
}
