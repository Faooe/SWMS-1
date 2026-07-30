<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {
    }

    /**
     * Display employee list.
     */
    public function index(Request $request): JsonResponse
    {
        $employees = $this->employeeService->getAll(
            $request->all()
        );

        return ResponseHelper::success(

            [
                'items' => EmployeeResource::collection(
                    $employees->items()
                ),

                'pagination' => [

                    'current_page' => $employees->currentPage(),

                    'last_page' => $employees->lastPage(),

                    'per_page' => $employees->perPage(),

                    'total' => $employees->total(),

                ],

            ],

            'Data karyawan berhasil diambil.'

        );
    }

    /**
     * Display employee detail.
     */
    public function show(int $id): JsonResponse
    {
        $employee = $this->employeeService->find($id);

        if (!$employee) {

            return ResponseHelper::error(

                'Data karyawan tidak ditemukan.',

                null,

                404

            );

        }

        return ResponseHelper::success(

            new EmployeeResource($employee),

            'Detail karyawan berhasil diambil.'

        );
    }

    /**
     * Store employee.
     */
    public function store(
        StoreEmployeeRequest $request
    ): JsonResponse {

        $employee = $this->employeeService->create(
            $request->validated()
        );

        return ResponseHelper::success(

            new EmployeeResource($employee),

            'Data karyawan berhasil ditambahkan.',

            201

        );
    }

    /**
     * Update employee.
     *
     * Route parameter WAJIB bernama {employee} (implicit route model
     * binding) karena App\Http\Requests\UpdateEmployeeRequest membaca
     * $this->route('employee') untuk validasi unique/ignore. Otorisasi
     * company tetap dijaga di EmployeeService::update() lewat
     * authorizeCompany(), jadi employee milik company lain tetap ditolak
     * (403) walau ID-nya ditebak.
     */
    public function update(
        UpdateEmployeeRequest $request,
        Employee $employee
    ): JsonResponse {

        try {

            $employee = $this->employeeService->update(
                $employee,
                $request->validated()
            );

        } catch (ValidationException $exception) {

            return ResponseHelper::error(
                'Data tidak valid.',
                $exception->errors(),
                422
            );

        }

        return ResponseHelper::success(

            new EmployeeResource($employee),

            'Data karyawan berhasil diperbarui.'

        );
    }

    /**
     * Delete employee.
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $this->employeeService->delete($employee);

        return ResponseHelper::success(

            null,

            'Data karyawan berhasil dihapus.'

        );
    }

    /**
     * Toggle employee active status.
     */
    public function toggleStatus(Employee $employee): JsonResponse
    {
        $employee = $this->employeeService->toggleStatus($employee);

        return ResponseHelper::success(

            new EmployeeResource($employee),

            $employee->is_active
                ? 'Karyawan berhasil diaktifkan.'
                : 'Karyawan berhasil dinonaktifkan.'

        );
    }
}
