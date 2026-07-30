<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Position Management (Company Admin / Super Admin)
    |--------------------------------------------------------------------------
    |
    | Setara app/Http/Controllers/Web/PositionController.php. Route
    | dilindungi middleware 'role:SUPER_ADMIN' -- lihat routes/api.php.
    |
    */

    /**
     * List (paginated, termasuk yang nonaktif)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Position::query()->forCurrentCompany();

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

        $positions = $query
            ->orderBy('name')
            ->paginate($request->integer('per_page') ?: 15);

        return ResponseHelper::success(
            [
                'items' => MasterResource::collection($positions->items()),
                'pagination' => [
                    'current_page' => $positions->currentPage(),
                    'last_page' => $positions->lastPage(),
                    'per_page' => $positions->perPage(),
                    'total' => $positions->total(),
                ],
            ],
            'Data position berhasil diambil.'
        );
    }

    /**
     * Detail
     */
    public function show(Position $position): JsonResponse
    {
        $this->authorizeCompany($position);

        return ResponseHelper::success(
            $position,
            'Detail position berhasil diambil.'
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
                Rule::unique('positions', 'code')->where(
                    fn ($query) => $query->where('company_id', $companyId)
                ),
            ],

            'name' => ['required', 'string', 'max:100'],

            'description' => ['nullable', 'string'],

            'is_active' => ['nullable', 'boolean'],

        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $data['company_id'] = $companyId;

        $position = Position::create($data);

        return ResponseHelper::success(
            $position,
            'Position berhasil ditambahkan.',
            201
        );
    }

    /**
     * Update
     */
    public function update(Request $request, Position $position): JsonResponse
    {
        $this->authorizeCompany($position);

        $data = $request->validate([

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('positions', 'code')
                    ->where(fn ($query) => $query->where('company_id', $position->company_id))
                    ->ignore($position->id),
            ],

            'name' => ['required', 'string', 'max:100'],

            'description' => ['nullable', 'string'],

            'is_active' => ['nullable', 'boolean'],

        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $position->update($data);

        return ResponseHelper::success(
            $position->fresh(),
            'Position berhasil diperbarui.'
        );
    }

    /**
     * Delete
     */
    public function destroy(Position $position): JsonResponse
    {
        $this->authorizeCompany($position);

        if ($position->employmentHistories()->exists()) {

            return ResponseHelper::error(
                'Position tidak bisa dihapus karena masih digunakan oleh Employee.',
                null,
                422
            );

        }

        $position->delete();

        return ResponseHelper::success(
            null,
            'Position berhasil dihapus.'
        );
    }

    /**
     * Toggle Status
     */
    public function toggleStatus(Position $position): JsonResponse
    {
        $this->authorizeCompany($position);

        $position->update([
            'is_active' => !$position->is_active,
        ]);

        return ResponseHelper::success(
            $position->fresh(),
            $position->is_active
                ? 'Position berhasil diaktifkan.'
                : 'Position berhasil dinonaktifkan.'
        );
    }

    /**
     * Pastikan position yang diakses benar-benar milik company yang login
     */
    private function authorizeCompany(Position $position): void
    {
        abort_unless(
            $position->company_id === Auth::user()->company_id,
            403,
            'Anda tidak memiliki akses ke position ini.'
        );
    }
}
