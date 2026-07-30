<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OfficeRequest;
use App\Models\Office;
use App\Services\OfficeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Office Management (Company Admin / Super Admin)
    |--------------------------------------------------------------------------
    |
    | Setara app/Http/Controllers/Web/OfficeController.php -- web-nya
    | sendiri memang hanya menyediakan index/edit/update (tidak ada
    | create/delete Office secara manual), jadi API ini mengikuti scope
    | yang sama. Route dilindungi middleware 'role:SUPER_ADMIN'.
    |
    */

    public function __construct(
        protected OfficeService $officeService
    ) {
    }

    /**
     * List Office (paginated)
     */
    public function index(Request $request): JsonResponse
    {
        $offices = $this->officeService->getOffices(
            $request->only([
                'search',
                'province',
                'city',
                'status',
                'per_page',
            ])
        );

        return ResponseHelper::success(
            [
                'items' => $offices->items(),
                'pagination' => [
                    'current_page' => $offices->currentPage(),
                    'last_page' => $offices->lastPage(),
                    'per_page' => $offices->perPage(),
                    'total' => $offices->total(),
                ],
            ],
            'Data office berhasil diambil.'
        );
    }

    /**
     * Detail Office
     */
    public function show(int $id): JsonResponse
    {
        $office = $this->officeService->find($id);

        return ResponseHelper::success(
            $office,
            'Detail office berhasil diambil.'
        );
    }

    /**
     * Update Office
     */
    public function update(OfficeRequest $request, Office $office): JsonResponse
    {
        $office = $this->officeService->update(
            $office,
            $request->validated()
        );

        return ResponseHelper::success(
            $office,
            'Office berhasil diperbarui.'
        );
    }
}
