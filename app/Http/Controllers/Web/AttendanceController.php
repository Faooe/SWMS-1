<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Exports\AttendanceExport;
use App\Services\AttendanceManagementService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceManagementService $attendanceService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('attendance.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Detail
    |--------------------------------------------------------------------------
    */

    public function show(int $attendance)
    {
        return view(
            'attendance.show',
            [

                'attendance' => $this->attendanceService
                    ->find($attendance),

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export PDF (per Bulan)
    |--------------------------------------------------------------------------
    |
    | Sengaja TIDAK memakai getAttendances() (yang dipaginate untuk halaman
    | list) -- export laporan harus berisi SEMUA baris dalam satu bulan,
    | bukan cuma satu halaman (misal 10 baris) seperti sebelumnya.
    |
    */

    public function exportPdf(Request $request)
    {
        [$year, $month] = $this->resolveExportPeriod($request);

        $filters = $request->only(['search', 'office', 'status']);

        $attendances = $this->attendanceService
            ->getForMonth($year, $month, $filters);

        $statistics = $this->attendanceService
            ->statistics($year, $month);

        $pdf = Pdf::loadView(
            'attendance.pdf',
            [

                'attendances' => $attendances,

                'statistics' => $statistics,

                'period' => Carbon::create($year, $month, 1),

            ]
        )->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->download(

            'attendance-report-' .
            $year . '-' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) .
            '.pdf'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export Excel (per Bulan) -- Fitur Premium
    |--------------------------------------------------------------------------
    |
    | Hanya bisa diakses company dengan subscription_plan selain Free.
    |
    */

    public function exportExcel(Request $request)
    {
        $company = $request->user()->company;

        abort_unless(
            $company && $company->isPremium(),
            403,
            'Export Excel hanya tersedia untuk paket Premium. Silakan upgrade subscription Anda.'
        );

        [$year, $month] = $this->resolveExportPeriod($request);

        $filters = $request->only(['search', 'office', 'status']);

        $attendances = $this->attendanceService
            ->getForMonth($year, $month, $filters);

        $filename = 'attendance-report-' .
            $year . '-' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) .
            '.xlsx';

        return Excel::download(
            new AttendanceExport($attendances, $year, $month),
            $filename
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Export Period
    |--------------------------------------------------------------------------
    |
    | Menerima query string ?month=YYYY-MM (dari input type="month" di form
    | export), default ke bulan berjalan kalau tidak diisi / formatnya salah.
    |
    */

    private function resolveExportPeriod(Request $request): array
    {
        $month = $request->query('month');

        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {

            [$year, $month] = explode('-', $month);

            return [(int) $year, (int) $month];

        }

        return [now()->year, now()->month];
    }
}