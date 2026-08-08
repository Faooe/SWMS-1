<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Statistik "Performa Employee" (attendance + assignment selesai) per
 * bulan -- dipakai di halaman Detail Employee (web) & tab Performance di
 * Employee Detail (Flutter mobile), termasuk data untuk export PDF/Excel.
 *
 * SENGAJA dipisah dari AttendanceManagementService (yang scope-nya
 * SELURUH employee company) karena di sini semua query di-scope ke SATU
 * employee tertentu, dan digabung juga dengan data Assignment (yang
 * AttendanceManagementService tidak punya).
 */
class EmployeePerformanceService
{
    /*
    |--------------------------------------------------------------------------
    | Resolve Range
    |--------------------------------------------------------------------------
    |
    | Terima query string ?from=YYYY-MM&to=YYYY-MM. Default: bulan
    | berjalan saja (from == to == bulan ini) kalau parameter tidak
    | diisi/formatnya salah. Batas maksimal 24 bulan sekali tarik supaya
    | tidak ada yang iseng minta rentang 20 tahun dan bikin query berat.
    |
    */

    public function resolveRange(Request $request): array
    {
        $from = $this->parseMonth($request->query('from'));
        $to = $this->parseMonth($request->query('to'));

        $now = Carbon::now()->startOfMonth();

        $from ??= $to ?? $now;
        $to ??= $from;

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to, $from];
        }

        if ($from->diffInMonths($to) > 23) {
            $from = $to->copy()->subMonths(23);
        }

        return [$from, $to];
    }

    private function parseMonth(?string $value): ?Carbon
    {
        if (!$value || !preg_match('/^\d{4}-\d{2}$/', $value)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value.'-01')->startOfMonth();
        } catch (\Throwable) {
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Monthly Chart
    |--------------------------------------------------------------------------
    |
    | Satu baris per bulan dalam rentang $from..$to (inklusif), dipakai
    | untuk data grafik & juga baris "Ringkasan per Bulan" di export.
    |
    | PERFORMA: dulu ini nge-loop per bulan lalu jalanin 4 query COUNT()
    | terpisah per bulan (bisa sampai puluhan query buat rentang setahun)
    | -- sekarang cuma 2 query total (1 attendance + 1 assignment)
    | untuk SELURUH rentang, hasilnya diagregasi per-bulan di PHP lewat
    | aggregateAttendance()/aggregateAssignments().
    |
    */

    public function monthlyChart(Employee $employee, Carbon $from, Carbon $to): array
    {
        $start = $from->copy()->startOfMonth();
        $end = $to->copy()->endOfMonth();

        $attendanceByMonth = $this->aggregateAttendance($employee, $start, $end, 'Y-m');
        $assignmentByMonth = $this->aggregateAssignments($employee, $start, $end, 'Y-m');

        $period = CarbonPeriod::create($from->copy(), '1 month', $to->copy());

        return collect($period)
            ->map(function (Carbon $month) use ($attendanceByMonth, $assignmentByMonth) {

                $key = $month->format('Y-m');
                $attendance = $attendanceByMonth[$key] ?? ['total' => 0, 'present' => 0, 'late' => 0];

                return [
                    'year' => $month->year,
                    'month' => $month->month,
                    'label' => $month->translatedFormat('M Y'),
                    'attendance_total' => $attendance['total'],
                    'attendance_present' => $attendance['present'],
                    'attendance_late' => $attendance['late'],
                    'assignment_completed' => $assignmentByMonth[$key] ?? 0,
                ];

            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Daily Chart (khusus rentang 1 bulan)
    |--------------------------------------------------------------------------
    |
    | Satu baris per TANGGAL dalam SATU bulan -- dipakai untuk grafik
    | ketika rentang yang dipilih cuma 1 bulan, supaya grafiknya tidak
    | rata di satu titik doang (yang kalau pakai monthlyChart() cuma
    | menghasilkan 1 titik data untuk keseluruhan bulan).
    |
    | PERFORMA: sama seperti monthlyChart() -- dulu bisa sampai ~120
    | query terpisah (31 hari x 4 metrik), sekarang cuma 2 query total.
    |
    */

    public function dailyChart(Employee $employee, Carbon $month): array
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $attendanceByDay = $this->aggregateAttendance($employee, $start, $end, 'Y-m-d');
        $assignmentByDay = $this->aggregateAssignments($employee, $start, $end, 'Y-m-d');

        $period = CarbonPeriod::create($start, '1 day', $end);

        return collect($period)
            ->map(function (Carbon $day) use ($attendanceByDay, $assignmentByDay) {

                $key = $day->format('Y-m-d');
                $attendance = $attendanceByDay[$key] ?? ['total' => 0, 'present' => 0, 'late' => 0];

                return [
                    'date' => $key,
                    'label' => $day->translatedFormat('d M'),
                    'attendance_total' => $attendance['total'],
                    'attendance_present' => $attendance['present'],
                    'attendance_late' => $attendance['late'],
                    'assignment_completed' => $assignmentByDay[$key] ?? 0,
                ];

            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Aggregate Helpers (1 query untuk seluruh rentang, dikelompokkan
    | per-tanggal di PHP -- bukan per SQL date-format function, supaya
    | tetap kompatibel lintas driver DB/PostgreSQL/MySQL/SQLite tanpa
    | perlu raw SQL yang beda-beda per driver)
    |--------------------------------------------------------------------------
    */

    private function aggregateAttendance(Employee $employee, Carbon $start, Carbon $end, string $groupFormat): array
    {
        $rows = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', '>=', $start)
            ->whereDate('attendance_date', '<=', $end)
            ->get(['attendance_date', 'attendance_status']);

        $result = [];

        foreach ($rows as $row) {
            $key = Carbon::parse($row->attendance_date)->format($groupFormat);
            $result[$key]['total'] = ($result[$key]['total'] ?? 0) + 1;
            $result[$key]['present'] = ($result[$key]['present'] ?? 0)
                + ($row->attendance_status === 'Present' ? 1 : 0);
            $result[$key]['late'] = ($result[$key]['late'] ?? 0)
                + ($row->attendance_status === 'Late' ? 1 : 0);
        }

        return $result;
    }

    private function aggregateAssignments(Employee $employee, Carbon $start, Carbon $end, string $groupFormat): array
    {
        $rows = $employee->assignments()
            ->wherePivotNotNull('finished_at')
            ->wherePivot('status', 'Completed')
            ->wherePivot('finished_at', '>=', $start->copy()->startOfDay())
            ->wherePivot('finished_at', '<=', $end->copy()->endOfDay())
            ->get();

        $result = [];

        foreach ($rows as $assignment) {
            $key = Carbon::parse($assignment->pivot->finished_at)->format($groupFormat);
            $result[$key] = ($result[$key] ?? 0) + 1;
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Chart Data (auto pilih granularitas)
    |--------------------------------------------------------------------------
    |
    | - Rentang PERSIS 1 bulan -> harian (dailyChart), supaya grafik
    |   menunjukkan sebaran per tanggal dalam bulan itu.
    | - Rentang LEBIH dari 1 bulan -> per bulan (monthlyChart), seperti
    |   semula.
    |
    | Dipakai KHUSUS untuk grafik (chart). Ringkasan per Bulan di
    | export/PDF/Excel & stat card ringkasan TETAP selalu pakai
    | monthlyChart()+summary() apa adanya, tidak terpengaruh ini.
    |
    */

    public function chartData(Employee $employee, Carbon $from, Carbon $to): array
    {
        if ($from->isSameMonth($to)) {

            return [
                'granularity' => 'daily',
                'points' => $this->dailyChart($employee, $from),
            ];

        }

        return [
            'granularity' => 'monthly',
            'points' => $this->monthlyChart($employee, $from, $to),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Summary (Total Sepanjang Rentang)
    |--------------------------------------------------------------------------
    */

    public function summary(array $monthlyChart): array
    {
        $rows = collect($monthlyChart);

        return [
            'attendance_total' => $rows->sum('attendance_total'),
            'attendance_present' => $rows->sum('attendance_present'),
            'attendance_late' => $rows->sum('attendance_late'),
            'assignment_completed' => $rows->sum('assignment_completed'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Detail (untuk export, semua baris tanpa pagination)
    |--------------------------------------------------------------------------
    */

    public function attendanceDetail(Employee $employee, Carbon $from, Carbon $to): Collection
    {
        return Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', '>=', $from->copy()->startOfMonth())
            ->whereDate('attendance_date', '<=', $to->copy()->endOfMonth())
            ->with(['office'])
            ->orderBy('attendance_date')
            ->orderBy('check_in_time')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Detail (untuk export, hanya yang berstatus Completed
    | dalam rentang, diurutkan berdasar tanggal selesai)
    |--------------------------------------------------------------------------
    */

    public function assignmentDetail(Employee $employee, Carbon $from, Carbon $to): Collection
    {
        return $employee->assignments()
            ->wherePivot('status', 'Completed')
            ->wherePivot('finished_at', '>=', $from->copy()->startOfMonth())
            ->wherePivot('finished_at', '<=', $to->copy()->endOfMonth())
            ->orderByPivot('finished_at')
            ->get();
    }
}