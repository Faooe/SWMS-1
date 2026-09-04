<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use App\Services\EmployeePerformanceService;
use App\Exports\EmployeePerformanceExport;
use App\Support\Xlsx\MultiSheetXlsxWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService,
        protected EmployeePerformanceService $performanceService
    ) {
    }

    /**
     * Display Employee List
     */
    public function index()
    {
        return view('employee.index');
    }

    /**
     * Show Import Page
     */
    public function import()
    {
        return view('employee.import');
    }

    /**
     * Show Create Employee Form
     */
    public function create()
    {
        return view(

            'employee.create',

            $this->employeeService->createFormData()

        );
    }

    /**
     * Store Employee
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = $this->employeeService->create(

            $request->validated()

        );

        return redirect()

            ->route('employees.show', $employee)

            ->with(

                'success',

                'Employee berhasil dibuat.'

            );
    }

    /**
     * Employee Detail
     */
    public function show(Employee $employee)
    {
        $employee = $this->scopedEmployeeOrFail($employee);
        $company = $employee->company;

        return view('employee.show', [
            'employee' => $employee,
            'isPremium' => $company?->isPremium() ?? false,
        ]);
    }

    /**
     * Show Edit Employee Form
     */
    public function edit(Employee $employee)
    {
        $employee = $this->scopedEmployeeOrFail($employee);

        return view(
            'employee.edit',
            array_merge(
                $this->employeeService->createFormData(),
                ['employee' => $employee]
            )
        );
    }

    /**
     * Update Employee
     */
    public function update(
        UpdateEmployeeRequest $request,
        Employee $employee
    ) {

        $this->employeeService->update(

            $employee,

            $request->validated()

        );

        return redirect()

            ->route('employees.show', $employee)

            ->with(

                'success',

                'Employee berhasil diperbarui.'

            );

    }

    /**
     * Delete Employee
     */
    public function destroy(Employee $employee)
    {
        $this->employeeService->delete($employee);

        return redirect()

            ->route('employees.index')

            ->with(

                'success',

                'Employee berhasil dihapus.'

            );
    }

    /**
     * Toggle Employee Status
     */
    public function toggleStatus(Employee $employee)
    {
        $employee = $this->employeeService->toggleStatus($employee);

        return back()->with(

            'success',

            $employee->is_active

                ? 'Employee berhasil diaktifkan.'

                : 'Employee berhasil dinonaktifkan.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employee Performance (AJAX -- dipanggil dari halaman employee.show
    | tiap kali date-range picker berubah, lihat script di bawah
    | resources/views/employee/show.blade.php)
    |--------------------------------------------------------------------------
    */

    public function performance(Request $request, Employee $employee)
    {
        $employee = $this->scopedEmployeeOrFail($employee);
        $employee->loadMissing('company');

        // Legacy web sends `from` + `to` without `period`. Treat that as a
        // month range so the new HR recap resolver does not silently collapse
        // it to the first month only. Mobile already sends `period` explicitly.
        if (! $request->filled('period') && $request->filled('from') && $request->filled('to')) {
            $request->merge(['period' => 'range']);
        }

        [$from, $to, $period] = $this->performanceService->resolveRecapRange($request);
        $chart = $this->performanceService->chartData($employee, $from, $to);
        $attendance = $this->performanceService->attendanceSummary($employee, $from, $to);
        $assignment = $this->performanceService->assignmentSummary($employee, $from, $to);

        return response()->json([
            'range' => [
                'period' => $period,
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ],
            'chart' => $chart,
            'attendance_summary' => $attendance,
            'assignment_summary' => $assignment,

            // Backward-compatible fields used by the current Blade page.
            'summary' => [
                'attendance_total' => $attendance['records'],
                'attendance_present' => $attendance['present'],
                'attendance_late' => $attendance['late'],
                'assignment_completed' => $assignment['completed'],
            ],
            'review_summary' => [
                'approved' => $assignment['approved'],
                'pending_review' => $assignment['pending_review'],
                'needs_revision' => $assignment['needs_revision'],
                'expired' => $assignment['not_worked'],
                'late_revision_count' => $assignment['late_revision'],
                'rejected' => $assignment['rejected'],
            ],
        ]);
    }

    /**
     * Export PDF Performance (ringkasan per bulan + detail attendance +
     * detail assignment selesai). Query: ?months=1 (bulan berjalan saja,
     * default) atau ?months=3 (bulan berjalan + 2 bulan sebelumnya).
     */
    public function performanceExportPdf(Request $request, Employee $employee)
    {
        $employee = $this->scopedEmployeeOrFail($employee);
        $this->ensurePerformanceExportPremium($request);
        $export = $this->buildPerformanceExport($request, $employee);
        [$from, $to] = $this->performanceService->resolveExportRange($request);
        $attendanceSummary = $this->performanceService->attendanceSummary($employee, $from, $to);
        $assignmentSummary = $this->performanceService->assignmentSummary($employee, $from, $to);

        $pdf = Pdf::loadView('employee.performance-pdf', [
            'employee' => $employee,
            'export' => $export,
            'monthlyChart' => $export->monthlyChart(),
            'summary' => $export->summary(),
            'reviewSummary' => $export->reviewSummary(),
            'attendanceDetail' => $export->attendanceDetail(),
            'assignmentDetail' => $export->assignmentDetail(),
            'attendanceSummary' => $attendanceSummary,
            'assignmentSummary' => $assignmentSummary,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('rekap-hr-'.$employee->employee_number.'-'.$export->filenameSlug().'.pdf');
    }

    public function performanceExportExcel(Request $request, Employee $employee)
    {
        $employee = $this->scopedEmployeeOrFail($employee);
        $this->ensurePerformanceExportPremium($request);
        $export = $this->buildPerformanceExport($request, $employee);
        [$from, $to] = $this->performanceService->resolveExportRange($request);
        $attendance = $this->performanceService->attendanceSummary($employee, $from, $to);
        $assignment = $this->performanceService->assignmentSummary($employee, $from, $to);

        $hrRows = [
            ['Periode', $export->title()],
            ['Hari Kerja Efektif', $attendance['working_days']],
            ['Hari Hadir', $attendance['attended']],
            ['Tepat Waktu', $attendance['present']],
            ['Terlambat', $attendance['late']],
            ['Leave', $attendance['leave']],
            ['Permission', $attendance['permission']],
            ['Absent', $attendance['absent']],
            ['Attendance Rate (%)', $attendance['attendance_rate']],
            ['Punctuality Rate (%)', $attendance['punctuality_rate']],
            ['Total Jam Kerja', round($attendance['work_minutes'] / 60, 2)],
            ['Total Telat (menit)', $attendance['late_minutes']],
            ['Pulang Awal (menit)', $attendance['early_leave_minutes']],
            ['Overtime (menit)', $attendance['overtime_minutes']],
            ['Total Assignment', $assignment['total']],
            ['Assignment Completed', $assignment['completed']],
            ['Assignment In Progress', $assignment['in_progress']],
            ['Assignment Approved', $assignment['approved']],
            ['Pending Review', $assignment['pending_review']],
            ['Needs Revision', $assignment['needs_revision']],
            ['Rejected', $assignment['rejected']],
            ['Not Worked / Expired', $assignment['not_worked']],
            ['Late Revision', $assignment['late_revision']],
            ['Completion Rate (%)', $assignment['completion_rate']],
        ];

        $filename = 'rekap-hr-'.$employee->employee_number.'-'.$export->filenameSlug().'.xlsx';

        return MultiSheetXlsxWriter::make([
            ['title' => 'Rekap HR', 'headings' => ['Metrik', 'Nilai'], 'rows' => $hrRows],
            ['title' => 'Ringkasan Tren', 'headings' => $export->summaryHeadings(), 'rows' => $export->summaryRows()],
            ['title' => 'Detail Attendance', 'headings' => $export->attendanceHeadings(), 'rows' => $export->attendanceRows()],
            ['title' => 'Detail Assignment', 'headings' => $export->assignmentHeadings(), 'rows' => $export->assignmentRows()],
        ])->download($filename);
    }

    private function ensurePerformanceExportPremium(Request $request): void
    {
        $company = $request->user()?->company;
        abort_unless(
            $company && $company->isPremium(),
            403,
            'Export PDF/Excel Rekap HR tersedia mulai paket Premium Go. Silakan upgrade subscription Anda.'
        );
    }

    private function scopedEmployeeOrFail(Employee $employee): Employee
    {
        $scoped = $this->employeeService->find($employee->id);
        abort_unless($scoped, 404);

        return $scoped;
    }

    private function buildPerformanceExport(Request $request, Employee $employee): EmployeePerformanceExport
    {
        [$from, $to] = $this->performanceService->resolveExportRange($request);
        $chart = $this->performanceService->chartData($employee, $from, $to)['points'];
        $attendance = $this->performanceService->attendanceSummary($employee, $from, $to);
        $assignment = $this->performanceService->assignmentSummary($employee, $from, $to);
        $summary = [
            'attendance_total' => $attendance['records'],
            'attendance_present' => $attendance['present'],
            'attendance_late' => $attendance['late'],
            'assignment_completed' => $assignment['completed'],
        ];
        $review = [
            'approved' => $assignment['approved'],
            'pending_review' => $assignment['pending_review'],
            'needs_revision' => $assignment['needs_revision'],
            'expired' => $assignment['not_worked'],
            'late_revision_count' => $assignment['late_revision'],
            'rejected' => $assignment['rejected'],
        ];

        return new EmployeePerformanceExport(
            $employee,
            $from,
            $to,
            $chart,
            $summary,
            $this->performanceService->attendanceDetail($employee, $from, $to),
            $this->performanceService->assignmentDetail($employee, $from, $to),
            $review,
        );
    }
}