<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Team List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('team.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('team.create', [

            'departments' => Department::forCurrentCompany()->orderBy('name')->get(),

        ]);
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

        Team::create($data);

        return redirect()

            ->route('teams.index')

            ->with('success', 'Team berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Team $team)
    {
        $this->authorizeCompany($team);

        return view('team.edit', [

            'team' => $team,

            'departments' => Department::forCurrentCompany()->orderBy('name')->get(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Team $team)
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

        return redirect()

            ->route('teams.index')

            ->with('success', 'Team berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Team $team)
    {
        $this->authorizeCompany($team);

        if ($team->employmentHistories()->exists()) {

            return back()->with(

                'error',

                'Team tidak bisa dihapus karena masih digunakan oleh Employee.'

            );

        }

        $team->delete();

        return redirect()

            ->route('teams.index')

            ->with('success', 'Team berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Team $team)
    {
        $this->authorizeCompany($team);

        $team->update([

            'is_active' => !$team->is_active,

        ]);

        return back()->with(

            'success',

            $team->is_active

                ? 'Team berhasil diaktifkan.'

                : 'Team berhasil dinonaktifkan.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Pastikan team yang diakses benar-benar milik company yang login
    |--------------------------------------------------------------------------
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
