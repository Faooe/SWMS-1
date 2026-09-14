<?php

namespace App\Services;

use App\Models\Assignment;
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
                'assignmentEmployees as rejected_employee_count' => fn ($q) => $q->where('status', 'Rejected'),
                'assignmentEmployees as pending_review_employee_count' => fn ($q) => $q->where('review_status', 'Pending Review'),
                'assignmentEmployees as needs_revision_employee_count' => fn ($q) => $q->where('review_status', 'Needs Revision'),
                'assignmentEmployees as approved_employee_count' => fn ($q) => $q->where('review_status', 'Approved'),
                'assignmentEmployees as not_worked_employee_count' => fn ($q) => $q->whereIn('review_status', ['Not Worked', 'Expired']),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (! empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('assignment_number', 'ILIKE', "%{$search}%")
                    ->orWhere('title', 'ILIKE', "%{$search}%")
                    ->orWhere('location_name', 'ILIKE', "%{$search}%");

            });

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
                case 'Draft':
                    $query->where('assignments.status', 'Draft');
                    break;

                case 'Active':
                    $query->whereIn('assignments.status', ['Assigned', 'In Progress'])
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
                                ->whereIn('status', ['Assigned', 'Accepted', 'In Progress']);
                        });
                    break;

                case 'Assigned':
                    $query->where('assignments.status', 'Assigned')
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                'Pending Review',
                                'Needs Revision',
                            ]);
                        });
                    break;

                case 'In Progress':
                    // Tetap dipertahankan untuk Company Admin karena ini status
                    // operasional yang berguna untuk mengetahui assignment yang
                    // benar-benar sedang dikerjakan sebelum disubmit.
                    $query->where('assignments.status', 'In Progress')
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                'Pending Review',
                                'Needs Revision',
                            ]);
                        });
                    break;

                case 'Pending Review':
                    $query->whereHas('employees', function ($employeeQuery) {
                        $employeeQuery->where('assignment_employees.review_status', 'Pending Review');
                    });
                    break;

                case 'Needs Revision':
                    $query->whereHas('employees', function ($employeeQuery) {
                        $employeeQuery->where('assignment_employees.review_status', 'Needs Revision');
                    });
                    break;

                case 'Completed':
                    // Assignment global bisa sudah Completed segera setelah semua
                    // employee submit. Di UI Company, Completed baru berarti hasil
                    // sudah di-approve (manual maupun Auto Approve).
                    $query->where('assignments.status', 'Completed')
                        ->whereHas('employees', function ($employeeQuery) {
                            $employeeQuery->where('assignment_employees.review_status', 'Approved');
                        })
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                'Pending Review',
                                'Needs Revision',
                            ]);
                        });
                    break;

                case 'Rejected':
                    $query->whereHas('employees', function ($employeeQuery) {
                        $employeeQuery->where('assignment_employees.status', 'Rejected');
                    });
                    break;

                case 'Cancelled':
                    $query->where('assignments.status', 'Cancelled');
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
