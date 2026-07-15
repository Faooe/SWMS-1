<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}