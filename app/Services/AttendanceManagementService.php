<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Office;
use Carbon\Carbon;
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

                // Sama seperti find() -- eager load supaya AttendanceResource
                // (dipakai list ini juga) tidak N+1 saat baca office->name.
                'office',

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

                // Office langsung milik record attendance ini (BUKAN
                // employee.currentEmployment.office -- bisa beda kalau
                // karyawan sudah pindah office setelah tanggal absen ini).
                // Dibutuhkan AttendanceResource untuk latitude/longitude/
                // radius kantor di kartu GPS Validation.
                'office',

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


    /*
    |--------------------------------------------------------------------------
    | Premium Attendance Analytics
    |--------------------------------------------------------------------------
    |
    | Ringkasan lintas periode yang dipakai bersama oleh web + mobile.
    | Periode didasarkan pada attendance_date agar hasil konsisten dengan
    | laporan/export dan tidak bergantung pada waktu request dibuat.
    |
    */
    public function analytics(
        string $period = 'day',
        ?string $date = null,
        ?int $year = null,
        ?int $month = null
    ): array {
        $period = in_array($period, ['day', 'month', 'year', 'all'], true)
            ? $period
            : 'day';

        [$start, $end, $label] = $this->resolveAnalyticsRange(
            $period,
            $date,
            $year,
            $month
        );

        $query = Attendance::query()->forCurrentCompany();

        if ($start && $end) {
            $query->whereBetween('attendance_date', [
                $start->toDateString(),
                $end->toDateString(),
            ]);
        }

        $summary = $this->statusSummary(clone $query);

        $employeeRows = (clone $query)
            ->select('employee_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Present' THEN 1 ELSE 0 END) as present")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Late' THEN 1 ELSE 0 END) as late")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Leave' THEN 1 ELSE 0 END) as leave_count")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Permission' THEN 1 ELSE 0 END) as permission_count")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Absent' THEN 1 ELSE 0 END) as absent")
            ->with('employee:id,full_name,employee_number,photo')
            ->groupBy('employee_id')
            ->orderByRaw('COUNT(*) DESC')
            ->get()
            ->map(function ($row) {
                $present = (int) $row->present;
                $late = (int) $row->late;
                $total = (int) $row->total;
                $attended = $present + $late;

                return [
                    'employee_id' => (int) $row->employee_id,
                    'employee_name' => $row->employee?->full_name ?? '-',
                    'employee_number' => $row->employee?->employee_number,
                    'employee_photo_url' => $row->employee?->photo
                        ? secure_file_url($row->employee->photo)
                        : null,
                    'total' => $total,
                    'attended' => $attended,
                    'present' => $present,
                    'late' => $late,
                    'leave' => (int) $row->leave_count,
                    'permission' => (int) $row->permission_count,
                    'absent' => (int) $row->absent,
                    'attendance_rate' => $total > 0
                        ? round(($attended / $total) * 100, 1)
                        : 0.0,
                ];
            })
            ->values();

        return [
            'period' => $period,
            'label' => $label,
            'start_date' => $start?->toDateString(),
            'end_date' => $end?->toDateString(),
            'summary' => array_merge($summary, [
                'employees_covered' => $employeeRows->count(),
                'attendance_rate' => $summary['total'] > 0
                    ? round((($summary['present'] + $summary['late']) / $summary['total']) * 100, 1)
                    : 0.0,
            ]),
            'by_employee' => $employeeRows,
        ];
    }

    private function statusSummary(Builder $query): array
    {
        $row = (clone $query)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Present' THEN 1 ELSE 0 END) as present")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Late' THEN 1 ELSE 0 END) as late")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Leave' THEN 1 ELSE 0 END) as leave_count")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Permission' THEN 1 ELSE 0 END) as permission_count")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Absent' THEN 1 ELSE 0 END) as absent")
            ->first();

        $present = (int) ($row->present ?? 0);
        $late = (int) ($row->late ?? 0);

        return [
            'total' => (int) ($row->total ?? 0),
            'attended' => $present + $late,
            'present' => $present,
            'late' => $late,
            'leave' => (int) ($row->leave_count ?? 0),
            'permission' => (int) ($row->permission_count ?? 0),
            'absent' => (int) ($row->absent ?? 0),
        ];
    }

    private function resolveAnalyticsRange(
        string $period,
        ?string $date,
        ?int $year,
        ?int $month
    ): array {
        $today = today();

        if ($period === 'all') {
            return [null, null, 'Semua Data'];
        }

        if ($period === 'year') {
            $selectedYear = $year ?: $today->year;
            $start = Carbon::create($selectedYear, 1, 1)->startOfDay();
            $end = Carbon::create($selectedYear, 12, 31)->endOfDay();

            return [$start, $end, 'Tahun ' . $selectedYear];
        }

        if ($period === 'month') {
            $selectedYear = $year ?: $today->year;
            $selectedMonth = ($month && $month >= 1 && $month <= 12)
                ? $month
                : $today->month;
            $start = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            return [$start, $end, $start->translatedFormat('F Y')];
        }

        try {
            $selectedDate = $date ? Carbon::parse($date) : $today->copy();
        } catch (\Throwable) {
            $selectedDate = $today->copy();
        }

        return [
            $selectedDate->copy()->startOfDay(),
            $selectedDate->copy()->endOfDay(),
            $selectedDate->translatedFormat('d F Y'),
        ];
    }
}