<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Services\AttendanceManagementService;
use App\Exports\AttendanceExport;
use App\Support\Xlsx\XlsxWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceManagementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Attendance Management Controller (Company Admin / Super Admin)
    |--------------------------------------------------------------------------
    |
    | Melengkapi endpoint untuk admin melihat absensi SELURUH karyawan di
    | company-nya (sebelumnya endpoint /attendance/* di API hanya untuk
    | absensi milik diri sendiri / employee). Memakai ulang
    | App\Services\AttendanceManagementService yang sama dengan web
    | (app/Http/Controllers/Web/AttendanceController), tanpa fitur
    | export PDF/Excel karena tidak relevan untuk aplikasi mobile.
    |
    */

    public function __construct(
        protected AttendanceManagementService $attendanceService
    ) {
    }

    /**
     * Attendance List (Company)
     */
    public function index(Request $request): JsonResponse
    {
        $attendances = $this->attendanceService->getAttendances(
            $request->only([
                'search',
                'office',
                'status',
                'date',
                'per_page',
            ])
        );

        return ResponseHelper::success(
            [
                'items' => AttendanceResource::collection(
                    $attendances->items()
                ),
                'pagination' => [
                    'current_page' => $attendances->currentPage(),
                    'last_page' => $attendances->lastPage(),
                    'per_page' => $attendances->perPage(),
                    'total' => $attendances->total(),
                ]
            ],
            'Data absensi karyawan berhasil diambil.'
        );
    }

    /**
     * Premium Attendance Analytics (Company).
     * period: day | month | year | all
     */
    public function analytics(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        abort_unless(
            $company && $company->isPremium(),
            403,
            'Attendance Analytics hanya tersedia untuk paket Premium. Silakan upgrade subscription Anda.'
        );

        $data = $request->validate([
            'period' => ['nullable', 'in:day,month,year,all'],
            'date' => ['nullable', 'date'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        return ResponseHelper::success(
            $this->attendanceService->analytics(
                $data['period'] ?? 'day',
                $data['date'] ?? null,
                isset($data['year']) ? (int) $data['year'] : null,
                isset($data['month']) ? (int) $data['month'] : null,
            ),
            'Analytics attendance berhasil diambil.'
        );
    }

    /**
     * Attendance Detail
     */
    public function show(int $id): JsonResponse
    {
        $attendance = $this->attendanceService->find($id);

        return ResponseHelper::success(
            new AttendanceResource($attendance),
            'Detail absensi berhasil diambil.'
        );
    }

    /**
     * Attendance Statistics (Company, per bulan)
     */
    public function statistics(Request $request): JsonResponse
    {
        return ResponseHelper::success(
            $this->attendanceService->statistics(
                $request->integer('year') ?: null,
                $request->integer('month') ?: null
            ),
            'Statistik absensi berhasil diambil.'
        );
    }

    /**
     * Export PDF (per Bulan) -- versi mobile dari
     * App\Http\Controllers\Web\AttendanceController::exportPdf(), dipakai
     * ulang view & filter yang sama, cuma dibungkus supaya bisa dipanggil
     * dengan Bearer token (bukan session cookie web).
     *
     * Sengaja TIDAK memakai getAttendances() (yang dipaginate untuk
     * halaman list) -- export laporan harus berisi SEMUA baris dalam satu
     * bulan, bukan cuma satu halaman.
     */
    public function exportPdf(Request $request)
    {
        [$year, $month] = $this->resolveExportPeriod($request);

        $filters = $request->only(['search', 'office', 'status']);

        $attendances = $this->attendanceService->getForMonth($year, $month, $filters);
        $statistics = $this->attendanceService->statistics($year, $month);

        $pdf = Pdf::loadView(
            'attendance.pdf',
            [
                'attendances' => $attendances,
                'statistics' => $statistics,
                'period' => Carbon::create($year, $month, 1),
            ]
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'attendance-report-' . $year . '-' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.pdf'
        );
    }

    /**
     * Export Excel (per Bulan) -- Fitur Premium, sama seperti web. Hanya
     * bisa diakses company dengan subscription_plan selain Free.
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

        $attendances = $this->attendanceService->getForMonth($year, $month, $filters);

        $filename = 'attendance-report-' . $year . '-' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.xlsx';

        $export = new AttendanceExport($attendances, $year, $month);

        return XlsxWriter::make(
            $export->title(),
            $export->headings(),
            $export->rows()
        )->download($filename);
    }

    /**
     * Terima query string ?month=YYYY-MM, default ke bulan berjalan kalau
     * tidak diisi / formatnya salah -- sama seperti resolveExportPeriod()
     * di Web\AttendanceController.
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
