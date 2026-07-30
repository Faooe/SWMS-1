<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Team Management (Company Admin / Super Admin)
    |--------------------------------------------------------------------------
    |
    | Setara app/Http/Controllers/Web/TeamController.php. Route
    | dilindungi middleware 'role:SUPER_ADMIN' -- lihat routes/api.php.
    |
    */

    /**
     * List (paginated, termasuk yang nonaktif)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Team::query()
            ->forCurrentCompany()
            ->with('department');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'ILIKE', "%{$search}%")
                    ->orWhere('name', 'ILIKE', "%{$search}%");
            });

        }

        if ($request->filled('department_id')) {

            $query->where('department_id', $request->department_id);

        }

        if ($request->has('is_active') && $request->is_active !== '') {

            $query->where(
                'is_active',
                filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)
            );

        }

        $teams = $query
            ->orderBy('name')
            ->paginate($request->integer('per_page') ?: 15);

        return ResponseHelper::success(
            [
                'items' => $teams->items(),
                'pagination' => [
                    'current_page' => $teams->currentPage(),
                    'last_page' => $teams->lastPage(),
                    'per_page' => $teams->perPage(),
                    'total' => $teams->total(),
                ],
            ],
            'Data team berhasil diambil.'
        );
    }

    /**
     * Detail
     */
    public function show(Team $team): JsonResponse
    {
        $this->authorizeCompany($team);

        $team->load('department');

        return ResponseHelper::success(
            $team,
            'Detail team berhasil diambil.'
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
                Rule::unique('teams', 'code')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'name' => ['required', 'string', 'max:100'],

            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'description' => ['nullable', 'string'],

            'is_active' => ['nullable', 'boolean'],

        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $data['company_id'] = $companyId;

        $team = Team::create($data);

        return ResponseHelper::success(
            $team->load('department'),
            'Team berhasil ditambahkan.',
            201
        );
    }

    /**
     * Update
     */
    public function update(Request $request, Team $team): JsonResponse
    {
        $this->authorizeCompany($team);

        $data = $request->validate([

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('teams', 'code')
                    ->where(fn ($query) => $query->where('company_id', $team->company_id))
                    ->ignore($team->id),
            ],

            'name' => ['required', 'string', 'max:100'],

            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where(
                    fn ($query) => $query->where('company_id', $team->company_id)
                ),
            ],

            'description' => ['nullable', 'string'],

            'is_active' => ['nullable', 'boolean'],

        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $team->update($data);

        return ResponseHelper::success(
            $team->fresh('department'),
            'Team berhasil diperbarui.'
        );
    }

    /**
     * Delete
     */
    public function destroy(Team $team): JsonResponse
    {
        $this->authorizeCompany($team);

        if ($team->employmentHistories()->exists()) {

            return ResponseHelper::error(
                'Team tidak bisa dihapus karena masih digunakan oleh Employee.',
                null,
                422
            );

        }

        $team->delete();

        return ResponseHelper::success(
            null,
            'Team berhasil dihapus.'
        );
    }

    /**
     * Toggle Status
     */
    public function toggleStatus(Team $team): JsonResponse
    {
        $this->authorizeCompany($team);

        $team->update([
            'is_active' => !$team->is_active,
        ]);

        return ResponseHelper::success(
            $team->fresh(),
            $team->is_active
                ? 'Team berhasil diaktifkan.'
                : 'Team berhasil dinonaktifkan.'
        );
    }

    /**
     * Pastikan team yang diakses benar-benar milik company yang login
     */
    private function authorizeCompany(Team $team): void
    {
        abort_unless(
            $team->company_id === Auth::user()->company_id,
            403,
            'Anda tidak memiliki akses ke team ini.'
        );
    }
}
