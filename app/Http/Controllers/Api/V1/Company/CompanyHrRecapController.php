<?php

namespace App\Http\Controllers\Api\V1\Company;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\CompanyHrRecapController as WebCompanyHrRecapController;
use App\Services\CompanyHrRecapService;
use App\Services\SecureFileService;
use App\Support\PaginationData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class CompanyHrRecapController extends Controller
{
    public function __construct(private readonly CompanyHrRecapService $recapService) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'search' => ['nullable', 'string', 'max:100'],
            'office_id' => ['nullable', 'integer'],
            'department_id' => ['nullable', 'integer'],
            'position_id' => ['nullable', 'integer'],
            'team_id' => ['nullable', 'integer'],
            'active' => ['nullable', 'in:0,1'],
            'sort' => ['nullable', 'in:name,attendance_low,absent_high,completion_low,not_worked_high'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $company = $request->user()->company;
        abort_unless($company, 404);
        $recap = $this->recapService->recap($company, $request);
        $rows = $recap['rows'];

        return ResponseHelper::success([
            'range' => $recap['range'],
            'summary' => $recap['summary'],
            'items' => $rows->items(),
            'pagination' => PaginationData::from($rows),
            'export' => [
                'available' => $company->isPremium(),
                'minimum_plan' => 'Premium Go',
                'current_plan' => $company->subscription_plan,
            ],
        ], 'Rekap HR perusahaan berhasil diambil.');
    }

    public function exportPdf(Request $request, WebCompanyHrRecapController $controller)
    {
        return $controller->exportPdf($request);
    }

    public function exportExcel(Request $request, WebCompanyHrRecapController $controller)
    {
        return $controller->exportExcel($request);
    }

    public function signature(Request $request): JsonResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 404);

        return ResponseHelper::success(
            $this->signaturePayload($company, $request),
            'Konfigurasi tanda tangan HR berhasil diambil.'
        );
    }

    public function updateSignature(Request $request): JsonResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 404);

        $data = $request->validate([
            'signer_name' => ['nullable', 'string', 'max:100'],
            'signer_title' => ['nullable', 'string', 'max:100'],
            'signature_scale' => ['nullable', 'integer', 'min:50', 'max:200'],
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
            'hr_signature_scale' => (int) ($data['signature_scale'] ?? 100),
        ]);

        if ($oldPath && $oldPath !== $path) {
            app(SecureFileService::class)->delete($oldPath);
        }

        return ResponseHelper::success(
            $this->signaturePayload($company->fresh(), $request),
            'Tanda tangan HR berhasil disimpan.'
        );
    }

    private function signaturePayload($company, Request $request): array
    {
        return [
            'url' => secure_file_url($company->hr_signature_path),
            'data_uri' => app(SecureFileService::class)->dataUri($company->hr_signature_path),
            'name' => $company->hr_signer_name ?: $request->user()?->username ?: 'HR Manager',
            'title' => $company->hr_signer_title ?: 'HR Manager',
            'scale' => max(50, min(200, (int) ($company->hr_signature_scale ?: 100))),
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
}
