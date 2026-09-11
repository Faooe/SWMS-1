<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Office;
use App\Models\Position;
use App\Models\Team;
use App\Services\CompanyHrRecapService;
use App\Services\SecureFileService;
use App\Support\Xlsx\MultiSheetXlsxWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

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
            'signature' => $this->signaturePayload($company, $request),
        ]);
    }

    public function updateSignature(Request $request)
    {
        $company = $request->user()->company;
        abort_unless($company, 404);

        $data = $request->validate([
            'signer_name' => ['nullable', 'string', 'max:100'],
            'signer_title' => ['nullable', 'string', 'max:100'],
            'signature_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'signature_data' => ['nullable', 'string', 'max:1500000'],
            'remove_signature' => ['nullable', 'boolean'],
        ]);

        $oldPath = $company->hr_signature_path;
        $path = $oldPath;

        if ($request->hasFile('signature_file')) {
            $path = app(SecureFileService::class)->store($request->file('signature_file'), 'hr-signatures');
        } elseif (filled($data['signature_data'] ?? null)) {
            $path = $this->storeDigitalSignature($data['signature_data']);
        } elseif ($request->boolean('remove_signature')) {
            $path = null;
        }

        $company->update([
            'hr_signature_path' => $path,
            'hr_signer_name' => filled($data['signer_name'] ?? null) ? $data['signer_name'] : null,
            'hr_signer_title' => filled($data['signer_title'] ?? null) ? $data['signer_title'] : null,
        ]);

        if ($oldPath && $oldPath !== $path) {
            app(SecureFileService::class)->delete($oldPath);
        }

        return back()->with('success', 'Tanda tangan HR untuk laporan berhasil disimpan.');
    }

    public function exportPdf(Request $request)
    {
        $company = $this->premiumCompany($request);
        $recap = $this->recapService->recap($company, $request, false);

        return Pdf::loadView('company-recap.pdf', [
            'company' => $company,
            'recap' => $recap,
            'hrSignature' => $this->signaturePayload($company, $request),
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
                'cellStyles' => $this->summaryStyles($summary),
                'columnWidths' => [30, 20],
                'autoFilter' => true,
            ],
            [
                'title' => 'Rekap per Employee',
                'headings' => $this->employeeHeadings(),
                'rows' => $recap['rows']->map(fn (array $row): array => $this->employeeRow($row))->all(),
                'cellStyles' => $recap['rows']->map(fn (array $row): array => $this->employeeStyles($row))->all(),
                'columnWidths' => [24, 16, 20, 22, 18, 20, 12, 10, 10, 10, 10, 12, 10, 16, 16, 12, 12, 14, 16, 16, 17, 16],
                'autoFilter' => true,
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
                'cellStyles' => $attendance->map(fn ($row): array => $this->attendanceStyles($row))->all(),
                'columnWidths' => [24, 16, 13, 13, 20, 22, 15, 14, 18],
                'autoFilter' => true,
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
                'cellStyles' => $assignments->map(fn ($row): array => $this->assignmentStyles($row))->all(),
                'columnWidths' => [24, 16, 18, 32, 12, 18, 19, 19, 18, 18],
                'autoFilter' => true,
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

    private function signaturePayload($company, Request $request): array
    {
        return [
            'url' => secure_file_url($company->hr_signature_path),
            'data_uri' => app(SecureFileService::class)->dataUri($company->hr_signature_path),
            'name' => $company->hr_signer_name ?: $request->user()?->username ?: 'HR Manager',
            'title' => $company->hr_signer_title ?: 'HR Manager',
        ];
    }

    private function storeDigitalSignature(string $dataUrl): string
    {
        if (! preg_match('/^data:image\/(png|jpeg|webp);base64,([A-Za-z0-9+\/=\s]+)$/', $dataUrl, $matches)) {
            throw ValidationException::withMessages(['signature_data' => 'Format tanda tangan digital tidak valid.']);
        }

        $content = base64_decode($matches[2], true);
        $image = $content === false ? false : @getimagesizefromstring($content);

        if ($content === false || $image === false || strlen($content) > 1048576) {
            throw ValidationException::withMessages(['signature_data' => 'Tanda tangan digital harus berupa gambar valid maksimal 1 MB.']);
        }

        $mime = $image['mime'] ?? '';
        $extensions = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp'];

        if (! isset($extensions[$mime])) {
            throw ValidationException::withMessages(['signature_data' => 'Gunakan tanda tangan PNG, JPG, atau WEBP.']);
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'swms-signature-');
        file_put_contents($temporaryPath, $content);

        try {
            $file = new UploadedFile($temporaryPath, 'hr-signature.'.$extensions[$mime], $mime, null, true);

            return app(SecureFileService::class)->store($file, 'hr-signatures');
        } finally {
            @unlink($temporaryPath);
        }
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

    private function summaryStyles(array $summary): array
    {
        $rows = [
            ['blue', 'blue'], ['blue', 'blue'], ['blue', 'blue'], ['blue', 'blue'], ['blue', 'blue'],
            ['blue', $this->rateStyle((float) $summary['attendance_rate'])], ['blue', 'green'], ['blue', 'amber'],
            ['blue', 'purple'], ['blue', 'amber'], ['blue', 'red'], ['blue', 'blue'], ['blue', 'green'],
            ['blue', 'red'], ['blue', 'amber'], ['blue', 'amber'], ['blue', 'purple'],
            ['blue', $this->rateStyle((float) $summary['completion_rate'])],
        ];

        return $rows;
    }

    private function employeeStyles(array $row): array
    {
        $styles = array_fill(0, count($this->employeeHeadings()), 'normal');
        $styles[7] = 'blue';
        $styles[8] = 'green';
        $styles[9] = 'amber';
        $styles[10] = 'purple';
        $styles[11] = 'amber';
        $styles[12] = 'red';
        $styles[13] = $this->rateStyle((float) $row['attendance_rate']);
        $styles[15] = 'green';
        $styles[16] = 'red';
        $styles[17] = 'amber';
        $styles[18] = 'amber';
        $styles[19] = 'purple';
        $styles[20] = $this->rateStyle((float) $row['completion_rate']);
        $styles[21] = $this->rateStyle((float) $row['performance_score']);

        return $styles;
    }

    private function attendanceStyles($row): array
    {
        $styles = array_fill(0, 9, 'normal');
        $styles[6] = $this->attendanceStatusStyle((string) $row->attendance_status);
        $styles[7] = ((int) $row->late_minutes) > 0 ? 'amber' : 'muted';

        return $styles;
    }

    private function assignmentStyles($row): array
    {
        $styles = array_fill(0, 10, 'normal');
        $styles[4] = $this->priorityStyle((string) ($row->assignment?->priority ?? ''));
        $styles[8] = $this->assignmentStatusStyle((string) $row->status);
        $styles[9] = $this->reviewStyle((string) ($row->review_status ?? ''));

        return $styles;
    }

    private function rateStyle(float $rate): string
    {
        return $rate >= 90 ? 'green' : ($rate >= 75 ? 'amber' : 'red');
    }

    private function attendanceStatusStyle(string $status): string
    {
        return match (strtolower(trim($status))) {
            'present', 'hadir' => 'green',
            'late', 'telat' => 'amber',
            'permission', 'izin' => 'amber',
            'leave', 'cuti' => 'purple',
            'absent', 'absen' => 'red',
            default => 'muted',
        };
    }

    private function assignmentStatusStyle(string $status): string
    {
        return match (strtolower(trim($status))) {
            'completed', 'selesai' => 'green',
            'rejected', 'ditolak' => 'red',
            'assigned', 'accepted', 'in progress', 'not worked', 'expired' => 'amber',
            default => 'muted',
        };
    }

    private function reviewStyle(string $status): string
    {
        return match (strtolower(trim($status))) {
            'approved', 'disetujui' => 'green',
            'needs revision', 'revisi' => 'purple',
            'pending review', 'menunggu review' => 'amber',
            'rejected', 'ditolak' => 'red',
            default => 'muted',
        };
    }

    private function priorityStyle(string $priority): string
    {
        return match (strtolower(trim($priority))) {
            'critical' => 'red',
            'high' => 'amber',
            'medium' => 'blue',
            'low' => 'muted',
            default => 'normal',
        };
    }
}
