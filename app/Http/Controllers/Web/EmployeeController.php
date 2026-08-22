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
        $company = $employee->company;

        return view(

            'employee.show',

            [

                'employee' => $this->employeeService->find(
                    $employee->id
                ),

                // Dipakai section Performance untuk tombol export Excel
                // (Fitur Premium, sama pola dengan halaman Attendance --
                // lihat app/Livewire/Attendance/Manager.php).
                'isPremium' => $company?->isPremium() ?? false,

            ]

        );
    }

    /**
     * Show Edit Employee Form
     */
    public function edit(Employee $employee)
    {
        return view(

            'employee.edit',

            array_merge(

                $this->employeeService->createFormData(),

                [

                    'employee' => $this->employeeService->find(
                        $employee->id
                    ),

                ]

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
        [$from, $to] = $this->performanceService->resolveRange($request);

        $monthlyChart = $this->performanceService->monthlyChart($employee, $from, $to);
        $summary = $this->performanceService->summary($monthlyChart);
        $reviewSummary = $this->performanceService->reviewSummary($employee, $from, $to);
        $chart = $this->performanceService->chartData($employee, $from, $to);

        return response()->json([

            'range' => [
                'from' => $from->format('Y-m'),
                'to' => $to->format('Y-m'),
            ],

            'chart' => $chart,

            'review_summary' => $reviewSummary,

            'summary' => $summary,

        ]);
    }

    /**
     * Export PDF Performance (ringkasan per bulan + detail attendance +
     * detail assignment selesai). Query: ?months=1 (bulan berjalan saja,
     * default) atau ?months=3 (bulan berjalan + 2 bulan sebelumnya).
     */
    public function performanceExportPdf(Request $request, Employee $employee)
    {
        $export = $this->buildPerformanceExport($request, $employee);

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
     * Export Excel Performance (3 sheet: Ringkasan, Detail Attendance,
     * Detail Assignment Selesai) -- Fitur Premium, sama seperti export
     * Excel Attendance (lihat Web\AttendanceController::exportExcel()).
     * Query: ?months=1|3, sama seperti performanceExportPdf().
     */
    public function performanceExportExcel(Request $request, Employee $employee)
    {
        $company = $request->user()->company;

        abort_unless(
            $company && $company->isPremium(),
            403,
            'Export Excel hanya tersedia untuk paket Premium. Silakan upgrade subscription Anda.'
        );

        $export = $this->buildPerformanceExport($request, $employee);

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

    private function buildPerformanceExport(Request $request, Employee $employee): EmployeePerformanceExport
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
}