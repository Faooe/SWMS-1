<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Platform\StoreCompanyRequest;
use App\Http\Requests\Platform\UpdateCompanyRequest;
use App\Http\Resources\Platform\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Versi API dari App\Http\Controllers\Platform\CompanyController (web).
 * Dipakai oleh CompaniesListScreen & (nantinya) form Tambah/Edit Company
 * di Flutter. Semua route di sini sudah dijaga middleware
 * ['auth:sanctum', 'platform'] di routes/api.php -- hanya user dengan
 * role PLATFORM_ADMIN yang bisa mengaksesnya.
 */
class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {
    }

    /**
     * Daftar company (search, filter status/plan, pagination).
     *
     * Query params: search, status (1|0), plan, per_page.
     */
    public function index(Request $request): JsonResponse
    {
        $companies = $this->companyService->getAll(
            $request->all()
        );

        return ResponseHelper::success(

            [

                'items' => CompanyResource::collection(
                    $companies->items()
                ),

                'pagination' => [

                    'current_page' => $companies->currentPage(),

                    'last_page' => $companies->lastPage(),

                    'per_page' => $companies->perPage(),

                    'total' => $companies->total(),

                ],

            ],

            'Data company berhasil diambil.'

        );
    }

    /**
     * Detail satu company.
     */
    public function show(int $id): JsonResponse
    {
        try {

            $company = $this->companyService->find($id);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return ResponseHelper::error(

                'Company tidak ditemukan.',

                null,

                404

            );

        }

        return ResponseHelper::success(

            new CompanyResource($company),

            'Detail company berhasil diambil.'

        );
    }

    /**
     * Buat company baru sekaligus akun Super Admin-nya.
     *
     * Mengembalikan username & password yang di-generate otomatis --
     * tampilkan SEKALI ke Platform Admin (sama seperti flow web), karena
     * password aslinya tidak disimpan dalam bentuk plain text.
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $result = $this->companyService->create(
            $request->validated()
        );

        return ResponseHelper::success(

            [

                'company' => new CompanyResource(
                    $result['company']
                ),

                'generated_username' => $result['username'],

                'generated_email' => $result['email'],

                'generated_password' => $result['password'],

            ],

            'Company berhasil dibuat.',

            201

        );
    }

    /**
     * Update data company (tidak termasuk kredensial Super Admin).
     */
    public function update(
        UpdateCompanyRequest $request,
        Company $company
    ): JsonResponse {

        $company = $this->companyService->update(
            $company,
            $request->validated()
        );

        return ResponseHelper::success(

            new CompanyResource($company),

            'Company berhasil diperbarui.'

        );
    }

    /**
     * Hapus company (soft delete).
     */
    public function destroy(Company $company): JsonResponse
    {
        $this->companyService->delete($company);

        return ResponseHelper::success(

            null,

            'Company berhasil dihapus.'

        );
    }

    /**
     * Aktifkan / nonaktifkan company.
     */
    public function toggleStatus(Company $company): JsonResponse
    {
        $company = $this->companyService->toggleStatus($company);

        return ResponseHelper::success(

            new CompanyResource($company),

            $company->is_active

                ? 'Company berhasil diaktifkan.'

                : 'Company berhasil dinonaktifkan.'

        );
    }
}
