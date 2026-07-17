<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Department List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('department.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    |
    | Menampilkan siapa saja karyawan yang berada di department ini,
    | beserta position dan team masing-masing.
    |
    */

    public function show(Department $department)
    {
        $this->authorizeCompany($department);

        $department->load([

            'teams' => fn ($query) => $query->orderBy('name'),

        ]);

        $employmentHistories = $department->employmentHistories()

            ->with(['employee', 'position', 'team'])

            ->where('is_current', true)

            ->whereHas('employee', fn ($query) => $query->forCurrentCompany())

            ->get()

            ->sortBy(fn ($history) => $history->employee?->full_name)

            ->values();

        return view('department.show', [

            'department' => $department,

            'employmentHistories' => $employmentHistories,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('department.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

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

        Department::create($data);

        return redirect()

            ->route('departments.index')

            ->with('success', 'Department berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Department $department)
    {
        $this->authorizeCompany($department);

        return view('department.edit', [

            'department' => $department,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Department $department)
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

        return redirect()

            ->route('departments.index')

            ->with('success', 'Department berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Department $department)
    {
        $this->authorizeCompany($department);

        if ($department->teams()->exists()) {

            return back()->with(

                'error',

                'Department tidak bisa dihapus karena masih memiliki Team.'

            );

        }

        $department->delete();

        return redirect()

            ->route('departments.index')

            ->with('success', 'Department berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Department $department)
    {
        $this->authorizeCompany($department);

        $department->update([

            'is_active' => !$department->is_active,

        ]);

        return back()->with(

            'success',

            $department->is_active

                ? 'Department berhasil diaktifkan.'

                : 'Department berhasil dinonaktifkan.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Pastikan department yang diakses benar-benar milik company yang login
    |--------------------------------------------------------------------------
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
