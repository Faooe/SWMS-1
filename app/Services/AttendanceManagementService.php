<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttendanceManagementService
{
    /*
    |--------------------------------------------------------------------------
    | Attendance Index Data
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): array
    {
        return [

            'attendances' => $this->getAttendances(

                $request->only([

                    'search',

                    'office',

                    'status',

                    'date',

                    'per_page',

                ])

            ),

            'statistics' => $this->statistics(),

            'offices' => Office::query()

                ->forCurrentCompany()

                ->orderBy('name')

                ->get(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance List
    |--------------------------------------------------------------------------
    */

    public function getAttendances(
        array $filters = []
    ): LengthAwarePaginator {

        $query = Attendance::query()

            ->forCurrentCompany()

            ->with([

                'employee.currentEmployment.office',

                'employee.currentEmployment.position',

                'assignment',

            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->whereHas(

                'employee',

                function ($q) use ($search) {

                    $q->where(

                        'full_name',

                        'ILIKE',

                        "%{$search}%"

                    )

                    ->orWhere(

                        'employee_number',

                        'ILIKE',

                        "%{$search}%"

                    );

                }

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Office
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['office'])) {

            $query->whereHas(

                'employee.currentEmployment',

                function ($q) use ($filters) {

                    $q->where(

                        'office_id',

                        $filters['office']

                    );

                }

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {

            $query->where(

                'attendance_status',

                $filters['status']

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date'])) {

            $query->whereDate(

                'attendance_date',

                $filters['date']

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Order
        |--------------------------------------------------------------------------
        */

        $query

            ->latest('attendance_date')

            ->latest('check_in_time');

        return $query->paginate(

            $filters['per_page'] ?? 10

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Detail
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): Attendance {

        return Attendance::query()

            ->forCurrentCompany()

            ->with([

                'employee.currentEmployment.office',

                'employee.currentEmployment.position',

                'assignment',

            ])

            ->findOrFail($id);

    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        $query = Attendance::query()

            ->forCurrentCompany()

            ->whereMonth(

                'attendance_date',

                now()->month

            )

            ->whereYear(

                'attendance_date',

                now()->year

            );

        return [

            'present' => (clone $query)

                ->where(

                    'attendance_status',

                    'Present'

                )

                ->count(),

            'late' => (clone $query)

                ->where(

                    'attendance_status',

                    'Late'

                )

                ->count(),

            'leave' => (clone $query)

                ->where(

                    'attendance_status',

                    'Leave'

                )

                ->count(),

            'permission' => (clone $query)

                ->where(

                    'attendance_status',

                    'Permission'

                )

                ->count(),

            'absent' => (clone $query)

                ->where(

                    'attendance_status',

                    'Absent'

                )

                ->count(),

            'total' => (clone $query)

                ->count(),

        ];

    }
}