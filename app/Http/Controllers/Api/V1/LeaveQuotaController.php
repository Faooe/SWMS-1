<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\LeaveQuotaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| Leave Quota Controller (Company Admin)
|--------------------------------------------------------------------------
|
| Company Admin biasanya TIDAK perlu menyentuh endpoint ini -- semua
| employee otomatis dapat jatah default (lihat
| LeaveQuotaService::DEFAULT_ANNUAL_QUOTA_DAYS). Endpoint ini cuma
| dipakai untuk kasus pengecualian, mis. seorang employee dapat
| tambahan/pengurangan jatah cuti khusus tahun tertentu.
|
| forCurrentCompany() TIDAK ada di App\Models\LeaveQuota, jadi scoping
| company dijaga di sini dengan memastikan $employee yang di-resolve
| route-model-binding memang milik company yang sedang login (lihat
| authorizeEmployee()) -- pola yang sama dipakai controller lain yang
| menerima model employee langsung dari route.
|
*/

class LeaveQuotaController extends Controller
{
    public function __construct(
        protected LeaveQuotaService $leaveQuotaService
    ) {
    }

    private function authorizeEmployee(Request $request, Employee $employee): ?JsonResponse
    {
        $companyId = $request->user()?->company_id;

        if ($companyId && $employee->company_id !== $companyId) {

            return ResponseHelper::error(
                'Employee tidak ditemukan.',
                null,
                404
            );

        }

        return null;
    }

    /**
     * Lihat Kuota Cuti Employee
     */
    public function show(Request $request, Employee $employee): JsonResponse
    {
        if ($error = $this->authorizeEmployee($request, $employee)) {

            return $error;

        }

        $year = (int) ($request->query('year') ?: now()->year);

        return ResponseHelper::success(
            $this->leaveQuotaService->summary($employee, $year),
            'Kuota cuti berhasil diambil.'
        );
    }

    /**
     * Sesuaikan Jatah Cuti Employee (untuk tahun tertentu)
     */
    public function update(Request $request, Employee $employee): JsonResponse
    {
        if ($error = $this->authorizeEmployee($request, $employee)) {

            return $error;

        }

        $validator = Validator::make($request->all(), [

            'year' => ['required', 'integer', 'min:2000', 'max:2100'],

            'total_days' => ['required', 'integer', 'min:0', 'max:255'],

        ]);

        if ($validator->fails()) {

            return ResponseHelper::error(
                $validator->errors()->first(),
                $validator->errors(),
                422
            );

        }

        $data = $validator->validated();

        $this->leaveQuotaService->setTotalDays(
            $employee,
            (int) $data['year'],
            (int) $data['total_days']
        );

        return ResponseHelper::success(
            $this->leaveQuotaService->summary($employee, (int) $data['year']),
            'Kuota cuti berhasil disesuaikan.'
        );
    }
}
