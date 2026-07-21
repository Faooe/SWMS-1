<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

        $query = $this->baseQuery($filters);

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
    | Get All Attendances for One Month (dipakai untuk export PDF/Excel)
    |--------------------------------------------------------------------------
    |
    | TIDAK dipaginate -- getAttendances() sengaja tidak dipakai untuk export
    | karena kalau ikut ->paginate(), hasil export cuma berisi satu halaman
    | (misal 10 baris) meskipun datanya sebulan penuh.
    |
    */

    public function getForMonth(
        int $year,
        int $month,
        array $filters = []
    ): Collection {

        $query = $this->baseQuery($filters);

        $query

            ->whereYear('attendance_date', $year)

            ->whereMonth('attendance_date', $month)

            ->orderBy('attendance_date')

            ->orderBy('check_in_time');

        return $query->get();

    }

    /*
    |--------------------------------------------------------------------------
    | Base Query (Search, Office, Status) -- dipakai bersama oleh
    | getAttendances() (list dengan pagination) dan getForMonth() (export
    | tanpa pagination)
    |--------------------------------------------------------------------------
    */

    private function baseQuery(array $filters = []): Builder
    {

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

        return $query;

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

    public function statistics(
        ?int $year = null,
        ?int $month = null
    ): array
    {
        $query = Attendance::query()

            ->forCurrentCompany()

            ->whereMonth(

                'attendance_date',

                $month ?? now()->month

            )

            ->whereYear(

                'attendance_date',

                $year ?? now()->year

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