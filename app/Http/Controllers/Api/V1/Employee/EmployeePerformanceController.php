<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Exports\EmployeePerformanceExport;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\EmployeePerformanceService;
use App\Support\Xlsx\MultiSheetXlsxWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Versi mobile (Bearer token) dari halaman "Performance" di Detail
 * Employee web -- lihat App\Http\Controllers\Web\EmployeeController
 * (method performance/performanceExportPdf/performanceExportExcel), yang
 * memakai ulang service & export class yang SAMA PERSIS supaya angka di
 * web & mobile selalu konsisten.
 */
class EmployeePerformanceController extends Controller
{
    public function __construct(
        protected EmployeePerformanceService $performanceService,
    ) {
    }

    /**
     * Statistik performa (chart per bulan/harian + ringkasan total).
     *
     * Query: ?from=YYYY-MM&to=YYYY-MM (opsional, default bulan berjalan).
     */
    public function show(Request $request, Employee $employee): JsonResponse
    {
        $this->authorizeEmployee($request, $employee);

        [$from, $to] = $this->performanceService->resolveRange($request);

        $monthlyChart = $this->performanceService->monthlyChart($employee, $from, $to);
        $summary = $this->performanceService->summary($monthlyChart);
        $reviewSummary = $this->performanceService->reviewSummary($employee, $from, $to);
        $chart = $this->performanceService->chartData($employee, $from, $to);

        return ResponseHelper::success(
            [
                'range' => [
                    'from' => $from->format('Y-m'),
                    'to' => $to->format('Y-m'),
                ],
                'chart' => $chart,
                'summary' => $summary,
                'review_summary' => $reviewSummary,
            ],
            'Statistik performa employee berhasil diambil.'
        );
    }

    /**
     * Export PDF (ringkasan per bulan + detail attendance + detail
     * assignment selesai). Query: ?months=1 (bulan berjalan saja, default)
     * atau ?months=3 (bulan berjalan + 2 bulan sebelumnya).
     */
    public function exportPdf(Request $request, Employee $employee)
    {
        $this->authorizeEmployee($request, $employee);

        $export = $this->buildExport($request, $employee);

        $pdf = Pdf::loadView(
            'employee.performance-pdf',
            [
                'employee' => $employee,
                'export' => $export,
                'monthlyChart' => $export->monthlyChart(),
                'summary' => $export->summary(),
                'reviewSummary' => $export->reviewSummary(),
                'attendanceDetail' => $export->attendanceDetail(),
                'assignmentDetail' => $export->assignmentDetail(),
            ]
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'performance-'.$employee->employee_number.'-'.$export->filenameSlug().'.pdf'
        );
    }

    /**
     * Export Excel (3 sheet: Ringkasan, Detail Attendance, Detail
     * Assignment Selesai) -- Fitur Premium, sama seperti export Excel
     * Attendance (lihat AttendanceManagementController::exportExcel()).
     * Query: ?months=1|3, sama seperti exportPdf().
     */
    public function exportExcel(Request $request, Employee $employee)
    {
        $this->authorizeEmployee($request, $employee);

        $company = $request->user()->company;

        abort_unless(
            $company && $company->isPremium(),
            403,
            'Export Excel hanya tersedia untuk paket Premium. Silakan upgrade subscription Anda.'
        );

        $export = $this->buildExport($request, $employee);

        $filename = 'performance-'.$employee->employee_number.'-'.$export->filenameSlug().'.xlsx';

        return MultiSheetXlsxWriter::make([
            [
                'title' => 'Ringkasan',
                'headings' => $export->summaryHeadings(),
                'rows' => $export->summaryRows(),
            ],
            [
                'title' => 'Detail Attendance',
                'headings' => $export->attendanceHeadings(),
                'rows' => $export->attendanceRows(),
            ],
            [
                'title' => 'Detail Assignment',
                'headings' => $export->assignmentHeadings(),
                'rows' => $export->assignmentRows(),
            ],
        ])->download($filename);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function buildExport(Request $request, Employee $employee): EmployeePerformanceExport
    {
        [$from, $to] = $this->performanceService->resolveExportRange($request);

        $monthlyChart = $this->performanceService->monthlyChart($employee, $from, $to);
        $summary = $this->performanceService->summary($monthlyChart);
        $reviewSummary = $this->performanceService->reviewSummary($employee, $from, $to);
        $attendanceDetail = $this->performanceService->attendanceDetail($employee, $from, $to);
        $assignmentDetail = $this->performanceService->assignmentDetail($employee, $from, $to);

        return new EmployeePerformanceExport(
            $employee,
            $from,
            $to,
            $monthlyChart,
            $summary,
            $attendanceDetail,
            $assignmentDetail,
            $reviewSummary,
        );
    }

    /**
     * Employee yang diakses harus milik company user yang sedang login
     * (kecuali Platform Admin) -- pola yang sama dengan
     * BaseService::authorizeCompany(), tapi controller ini tidak extends
     * BaseService jadi dituliskan ulang secara ringkas di sini.
     */
    private function authorizeEmployee(Request $request, Employee $employee): void
    {
        $user = $request->user();

        if (
            $user
            && method_exists($user, 'isPlatformAdmin')
            && $user->isPlatformAdmin()
        ) {
            return;
        }

        abort_unless(
            $user && $employee->company_id == $user->company_id,
            403,
            'You are not authorized to access this data.'
        );
    }
}
