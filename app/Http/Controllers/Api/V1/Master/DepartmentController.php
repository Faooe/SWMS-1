<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Department Management (Company Admin / Super Admin)
    |--------------------------------------------------------------------------
    |
    | Berbeda dengan MasterController::departments() (dropdown read-only,
    | hanya yang aktif), controller ini untuk CRUD penuh -- setara
    | app/Http/Controllers/Web/DepartmentController.php. Route dilindungi
    | middleware 'role:SUPER_ADMIN' -- lihat routes/api.php.
    |
    */

    /**
     * List (paginated, termasuk yang nonaktif)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Department::query()
            ->forCurrentCompany()
            ->withCount('teams');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'ILIKE', "%{$search}%")
                    ->orWhere('name', 'ILIKE', "%{$search}%");
            });

        }

        if ($request->has('is_active') && $request->is_active !== '') {

            $query->where(
                'is_active',
                filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)
            );

        }

        $departments = $query
            ->orderBy('name')
            ->paginate($request->integer('per_page') ?: 15);

        return ResponseHelper::success(
            [
                'items' => MasterResource::collection($departments->items()),
                'pagination' => [
                    'current_page' => $departments->currentPage(),
                    'last_page' => $departments->lastPage(),
                    'per_page' => $departments->perPage(),
                    'total' => $departments->total(),
                ],
            ],
            'Data department berhasil diambil.'
        );
    }

    /**
     * Detail
     */
    public function show(Department $department): JsonResponse
    {
        $this->authorizeCompany($department);

        $department->load([
            'teams' => fn ($query) => $query->orderBy('name'),
        ]);

        // Setara App\Http\Controllers\Web\DepartmentController::show() --
        // "Employee di Department ini" di web. Hanya employment history
        // yang sedang berjalan (is_current) yang dihitung, karena satu
        // employee bisa punya beberapa histori department dari waktu ke
        // waktu (mutasi/promosi).
        $employmentHistories = $department->employmentHistories()
            ->with(['employee', 'position', 'team'])
            ->where('is_current', true)
            ->whereHas('employee', fn ($query) => $query->forCurrentCompany())
            ->get()
            ->sortBy(fn ($history) => $history->employee?->full_name)
            ->values();

        $payload = $department->toArray();

        $payload['employees'] = $employmentHistories
            ->map(fn ($history) => [
                'id' => $history->employee?->id,
                'employee_number' => $history->employee?->employee_number,
                'full_name' => $history->employee?->full_name,
                'email' => $history->employee?->email,
                'is_active' => $history->employee?->is_active,
                'position' => $history->position?->name,
                'team' => $history->team?->name,
            ])
            ->values();

        return ResponseHelper::success(
            $payload,
            'Detail department berhasil diambil.'
        );
    }

    /**
     * Store
     */
    public function store(Request $request): JsonResponse
    {
        $companyId = Auth::user()?->company_id;

        $data = $request->validate([

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('departments', 'code')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'name' => ['required', 'string', 'max:100'],

            'description' => ['nullable', 'string'],

            'is_active' => ['nullable', 'boolean'],

        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $data['company_id'] = $companyId;

        $department = Department::create($data);

        return ResponseHelper::success(
            $department,
            'Department berhasil ditambahkan.',
            201
        );
    }

    /**
     * Update
     */
    public function update(Request $request, Department $department): JsonResponse
    {
        $this->authorizeCompany($department);

        $data = $request->validate([

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('departments', 'code')
                    ->where(fn ($query) => $query->where('company_id', $department->company_id))
                    ->ignore($department->id),
            ],

            'name' => ['required', 'string', 'max:100'],

            'description' => ['nullable', 'string'],

            'is_active' => ['nullable', 'boolean'],

        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $department->update($data);

        return ResponseHelper::success(
            $department->fresh(),
            'Department berhasil diperbarui.'
        );
    }

    /**
     * Delete
     */
    public function destroy(Department $department): JsonResponse
    {
        $this->authorizeCompany($department);

        if ($department->teams()->exists()) {

            return ResponseHelper::error(
                'Department tidak bisa dihapus karena masih memiliki Team.',
                null,
                422
            );

        }

        $department->delete();

        return ResponseHelper::success(
            null,
            'Department berhasil dihapus.'
        );
    }

    /**
     * Toggle Status
     */
    public function toggleStatus(Department $department): JsonResponse
    {
        $this->authorizeCompany($department);

        $department->update([
            'is_active' => !$department->is_active,
        ]);

        return ResponseHelper::success(
            $department->fresh(),
            $department->is_active
                ? 'Department berhasil diaktifkan.'
                : 'Department berhasil dinonaktifkan.'
        );
    }

    /**
     * Pastikan department yang diakses benar-benar milik company yang login
     */
    private function authorizeCompany(Department $department): void
    {
        abort_unless(
            $department->company_id === Auth::user()->company_id,
            403,
            'Anda tidak memiliki akses ke department ini.'
        );
    }
}
