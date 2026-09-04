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

class EmployeePerformanceController extends Controller
{
    public function __construct(protected EmployeePerformanceService $performanceService) {}

    public function show(Request $request, Employee $employee): JsonResponse
    {
        $this->authorizeEmployee($request, $employee);
        $employee->loadMissing('company');
        [$from,$to,$period] = $this->performanceService->resolveRecapRange($request);
        $chart = $this->performanceService->chartData($employee,$from,$to);
        $attendance = $this->performanceService->attendanceSummary($employee,$from,$to);
        $assignment = $this->performanceService->assignmentSummary($employee,$from,$to);
        $company = $request->user()?->company;

        return ResponseHelper::success([
            'range'=>[
                'period'=>$period,
                'from'=>$from->toDateString(),
                'to'=>$to->toDateString(),
                'label'=>$this->rangeLabel($from,$to,$period),
            ],
            'chart'=>$chart,
            'attendance_summary'=>$attendance,
            'assignment_summary'=>$assignment,
            // Keep old fields so old web/mobile clients do not break.
            'summary'=>[
                'attendance_total'=>$attendance['records'],
                'attendance_present'=>$attendance['present'],
                'attendance_late'=>$attendance['late'],
                'assignment_completed'=>$assignment['completed'],
            ],
            'review_summary'=>[
                'approved'=>$assignment['approved'], 'pending_review'=>$assignment['pending_review'],
                'needs_revision'=>$assignment['needs_revision'], 'expired'=>$assignment['not_worked'],
                'late_revision_count'=>$assignment['late_revision'], 'rejected'=>$assignment['rejected'],
            ],
            'company_standard'=>[
                'source'=>'Work Calendar Company',
                'working_days'=>$attendance['working_days'],
                'future_days_excluded'=>true,
            ],
            'export'=>[
                'available'=>(bool) ($company?->isPremium()),
                'minimum_plan'=>'Premium Go',
                'current_plan'=>$company?->subscription_plan ?? 'Free',
            ],
        ], 'Rekap HR employee berhasil diambil.');
    }

    public function exportPdf(Request $request, Employee $employee)
    {
        $this->authorizeEmployee($request,$employee); $this->ensurePremium($request);
        $export=$this->buildExport($request,$employee);
        [$from,$to] = $this->performanceService->resolveExportRange($request);
        $attendanceSummary=$this->performanceService->attendanceSummary($employee,$from,$to);
        $assignmentSummary=$this->performanceService->assignmentSummary($employee,$from,$to);
        $pdf=Pdf::loadView('employee.performance-pdf',[
            'employee'=>$employee,'export'=>$export,'monthlyChart'=>$export->monthlyChart(),
            'summary'=>$export->summary(),'reviewSummary'=>$export->reviewSummary(),
            'attendanceDetail'=>$export->attendanceDetail(),'assignmentDetail'=>$export->assignmentDetail(),
            'attendanceSummary'=>$attendanceSummary,'assignmentSummary'=>$assignmentSummary,
        ])->setPaper('a4','landscape');
        return $pdf->download('rekap-hr-'.$employee->employee_number.'-'.$export->filenameSlug().'.pdf');
    }

    public function exportExcel(Request $request, Employee $employee)
    {
        $this->authorizeEmployee($request,$employee); $this->ensurePremium($request);
        $export=$this->buildExport($request,$employee);
        [$from,$to] = $this->performanceService->resolveExportRange($request);
        $attendance = $this->performanceService->attendanceSummary($employee,$from,$to);
        $assignment = $this->performanceService->assignmentSummary($employee,$from,$to);
        $filename='rekap-hr-'.$employee->employee_number.'-'.$export->filenameSlug().'.xlsx';
        $hrRows = [
            ['Periode', $this->rangeLabel($from,$to,(string)$request->query('period','month'))],
            ['Hari Kerja Efektif', $attendance['working_days']],
            ['Hari Hadir', $attendance['attended']],
            ['Tepat Waktu', $attendance['present']],
            ['Terlambat', $attendance['late']],
            ['Leave', $attendance['leave']],
            ['Permission', $attendance['permission']],
            ['Absent', $attendance['absent']],
            ['Attendance Rate (%)', $attendance['attendance_rate']],
            ['Punctuality Rate (%)', $attendance['punctuality_rate']],
            ['Total Jam Kerja', round($attendance['work_minutes']/60,2)],
            ['Total Telat (menit)', $attendance['late_minutes']],
            ['Pulang Awal (menit)', $attendance['early_leave_minutes']],
            ['Overtime (menit)', $attendance['overtime_minutes']],
            ['Total Assignment', $assignment['total']],
            ['Assignment Completed', $assignment['completed']],
            ['Assignment Approved', $assignment['approved']],
            ['Pending Review', $assignment['pending_review']],
            ['Needs Revision', $assignment['needs_revision']],
            ['Rejected', $assignment['rejected']],
            ['Not Worked / Expired', $assignment['not_worked']],
            ['Late Revision', $assignment['late_revision']],
            ['Completion Rate (%)', $assignment['completion_rate']],
        ];
        return MultiSheetXlsxWriter::make([
            ['title'=>'Rekap HR','headings'=>['Metrik','Nilai'],'rows'=>$hrRows],
            ['title'=>'Ringkasan Tren','headings'=>$export->summaryHeadings(),'rows'=>$export->summaryRows()],
            ['title'=>'Detail Attendance','headings'=>$export->attendanceHeadings(),'rows'=>$export->attendanceRows()],
            ['title'=>'Detail Assignment','headings'=>$export->assignmentHeadings(),'rows'=>$export->assignmentRows()],
        ])->download($filename);
    }

    private function buildExport(Request $request, Employee $employee): EmployeePerformanceExport
    {
        [$from,$to]=$this->performanceService->resolveExportRange($request);
        $chart=$this->performanceService->chartData($employee,$from,$to)['points'];
        $attendance=$this->performanceService->attendanceSummary($employee,$from,$to);
        $assignment=$this->performanceService->assignmentSummary($employee,$from,$to);
        $summary=['attendance_total'=>$attendance['records'],'attendance_present'=>$attendance['present'],'attendance_late'=>$attendance['late'],'assignment_completed'=>$assignment['completed']];
        $review=['approved'=>$assignment['approved'],'pending_review'=>$assignment['pending_review'],'needs_revision'=>$assignment['needs_revision'],'expired'=>$assignment['not_worked'],'late_revision_count'=>$assignment['late_revision'],'rejected'=>$assignment['rejected']];
        return new EmployeePerformanceExport($employee,$from,$to,$chart,$summary,$this->performanceService->attendanceDetail($employee,$from,$to),$this->performanceService->assignmentDetail($employee,$from,$to),$review);
    }

    private function ensurePremium(Request $request): void
    {
        $company=$request->user()?->company;
        abort_unless($company && $company->isPremium(),403,'Export PDF/Excel Rekap HR tersedia mulai paket Premium Go. Silakan upgrade subscription Anda.');
    }

    private function rangeLabel($from,$to,string $period): string
    {
        if ($period==='today') return $from->translatedFormat('d F Y');
        if ($period==='month') return $from->translatedFormat('F Y');
        if ($period==='year') return $from->format('Y');
        return $from->translatedFormat('M Y').' - '.$to->translatedFormat('M Y');
    }

    private function authorizeEmployee(Request $request, Employee $employee): void
    {
        $user=$request->user();
        if ($user && method_exists($user,'isPlatformAdmin') && $user->isPlatformAdmin()) return;
        abort_unless($user && $employee->company_id == $user->company_id,403,'You are not authorized to access this data.');
    }
}
