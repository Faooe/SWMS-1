<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Office;
use App\Models\Position;
use App\Models\Team;
use App\Services\CompanyHrRecapService;
use App\Support\Xlsx\MultiSheetXlsxWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CompanyHrRecapController extends Controller
{
    public function __construct(private readonly CompanyHrRecapService $recapService) {}

    public function index(Request $request)
    {
        $company = $request->user()->company;
        abort_unless($company, 404);

        return view('company-recap.index', [
            'recap' => $this->recapService->recap($company, $request),
            'options' => $this->options($company->id),
            'isPremium' => $company->isPremium(),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $company = $this->premiumCompany($request);
        $recap = $this->recapService->recap($company, $request, false);

        return Pdf::loadView('company-recap.pdf', [
            'company' => $company,
            'recap' => $recap,
        ])->setPaper('a4', 'landscape')->download(
            'rekap-hr-perusahaan-'.$recap['range']['from'].'_'.$recap['range']['to'].'.pdf'
        );
    }

    public function exportExcel(Request $request)
    {
        $company = $this->premiumCompany($request);
        $recap = $this->recapService->recap($company, $request, false);
        [$from, $to] = $this->recapService->resolveRange($request);
        $attendance = $this->recapService->attendanceDetails($company, $recap['employee_ids'], $from, $to);
        $assignments = $this->recapService->assignmentDetails($company, $recap['employee_ids'], $from, $to);
        $summary = $recap['summary'];

        return MultiSheetXlsxWriter::make([
            [
                'title' => 'Ringkasan Perusahaan',
                'headings' => ['Metrik', 'Nilai'],
                'rows' => [
                    ['Perusahaan', $company->name],
                    ['Periode', $recap['range']['label']],
                    ['Jumlah Employee', $summary['employees']],
                    ['Employee Aktif', $summary['active_employees']],
                    ['Hari Kerja', $recap['range']['working_days']],
                    ['Attendance Rate (%)', $summary['attendance_rate']],
                    ['Present', $summary['present']],
                    ['Late', $summary['late']],
                    ['Leave', $summary['leave']],
                    ['Permission', $summary['permission']],
                    ['Absent', $summary['absent']],
                    ['Total Assignment', $summary['assignment_total']],
                    ['Assignment Completed', $summary['assignment_completed']],
                    ['Rejected', $summary['assignment_rejected']],
                    ['Not Worked', $summary['assignment_not_worked']],
                    ['Pending Review', $summary['assignment_pending_review']],
                    ['Needs Revision', $summary['assignment_needs_revision']],
                    ['Completion Rate (%)', $summary['completion_rate']],
                ],
            ],
            [
                'title' => 'Rekap per Employee',
                'headings' => $this->employeeHeadings(),
                'rows' => $recap['rows']->map(fn (array $row): array => $this->employeeRow($row))->all(),
            ],
            [
                'title' => 'Detail Attendance',
                'headings' => ['Employee', 'NIP', 'Tanggal', 'Check In', 'Check Out', 'Office', 'Status', 'Telat (menit)', 'Jam Kerja (menit)'],
                'rows' => $attendance->map(fn ($row): array => [
                    $row->employee?->full_name ?? '-',
                    $row->employee?->employee_number ?? '-',
                    $row->attendance_date->format('d/m/Y'),
                    optional($row->check_in_time)->format('H:i') ?? '-',
                    optional($row->check_out_time)->format('H:i') ?? '-',
                    $row->office?->name ?? '-',
                    $row->attendance_status,
                    $row->late_minutes,
                    $row->work_minutes,
                ])->all(),
            ],
            [
                'title' => 'Detail Assignment',
                'headings' => ['Employee', 'NIP', 'No. Assignment', 'Judul', 'Prioritas', 'Tipe', 'Ditugaskan', 'Selesai', 'Status', 'Review'],
                'rows' => $assignments->map(fn ($row): array => [
                    $row->employee?->full_name ?? '-',
                    $row->employee?->employee_number ?? '-',
                    $row->assignment?->assignment_number ?? '-',
                    $row->assignment?->title ?? '-',
                    $row->assignment?->priority ?? '-',
                    $row->assignment?->assignment_type ?? '-',
                    optional($row->assigned_at)->format('d/m/Y H:i') ?? '-',
                    optional($row->finished_at)->format('d/m/Y H:i') ?? '-',
                    $row->status,
                    $row->review_status ?? '-',
                ])->all(),
            ],
        ])->download('rekap-hr-perusahaan-'.$recap['range']['from'].'_'.$recap['range']['to'].'.xlsx');
    }

    private function premiumCompany(Request $request)
    {
        $company = $request->user()->company;
        abort_unless($company && $company->isPremium(), 403, 'Export Rekap HR Perusahaan tersedia mulai paket Premium Go.');

        return $company;
    }

    private function options(int $companyId): array
    {
        return [
            'offices' => Office::query()->where('company_id', $companyId)->orderBy('name')->get(['id', 'name']),
            'departments' => Department::query()->where('company_id', $companyId)->orderBy('name')->get(['id', 'name']),
            'positions' => Position::query()->where('company_id', $companyId)->orderBy('name')->get(['id', 'name']),
            'teams' => Team::query()->where('company_id', $companyId)->orderBy('name')->get(['id', 'name']),
        ];
    }

    private function employeeHeadings(): array
    {
        return [
            'Employee', 'NIP', 'Department', 'Position', 'Team', 'Office', 'Hari Kerja',
            'Hadir', 'Tepat', 'Telat', 'Leave', 'Permission', 'Absent', 'Attendance Rate (%)',
            'Total Assignment', 'Completed', 'Rejected', 'Not Worked', 'Pending Review',
            'Needs Revision', 'Completion Rate (%)', 'Performance Score',
        ];
    }

    private function employeeRow(array $row): array
    {
        return [
            $row['employee_name'], $row['employee_number'], $row['department'], $row['position'],
            $row['team'], $row['office'], $row['working_days'], $row['attended'], $row['present'],
            $row['late'], $row['leave'], $row['permission'], $row['absent'], $row['attendance_rate'],
            $row['assignment_total'], $row['assignment_completed'], $row['assignment_rejected'],
            $row['assignment_not_worked'], $row['assignment_pending_review'],
            $row['assignment_needs_revision'], $row['completion_rate'], $row['performance_score'],
        ];
    }
}
