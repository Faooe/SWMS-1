<?php

namespace App\Http\Controllers\Api\V1\Assignment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Versi mobile dari App\Http\Controllers\Web\AssignmentSettingsController
 * -- durasi revisi default & mode Auto Approve untuk company.
 */
class AssignmentSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        return ResponseHelper::success(
            [
                'assignment_revision_minutes' => $company->assignment_revision_minutes,
                'assignment_auto_approve' => (bool) $company->assignment_auto_approve,
            ],
            'Pengaturan assignment berhasil diambil.'
        );
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([

            'assignment_revision_minutes' => ['required', 'integer', 'min:5', 'max:43200'],

            'assignment_auto_approve' => ['required', 'boolean'],

        ]);

        $company = $request->user()->company;

        $company->update($data);

        return ResponseHelper::success(
            [
                'assignment_revision_minutes' => $company->assignment_revision_minutes,
                'assignment_auto_approve' => (bool) $company->assignment_auto_approve,
            ],
            'Pengaturan assignment berhasil disimpan.'
        );
    }
}
