<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Position List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('position.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('position.create');
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

        Position::create($data);

        return redirect()

            ->route('positions.index')

            ->with('success', 'Position berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Position $position)
    {
        $this->authorizeCompany($position);

        return view('position.edit', [

            'position' => $position,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Position $position)
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

        return redirect()

            ->route('positions.index')

            ->with('success', 'Position berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Position $position)
    {
        $this->authorizeCompany($position);

        if ($position->employmentHistories()->exists()) {

            return back()->with(

                'error',

                'Position tidak bisa dihapus karena masih digunakan oleh Employee.'

            );

        }

        $position->delete();

        return redirect()

            ->route('positions.index')

            ->with('success', 'Position berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Position $position)
    {
        $this->authorizeCompany($position);

        $position->update([

            'is_active' => !$position->is_active,

        ]);

        return back()->with(

            'success',

            $position->is_active

                ? 'Position berhasil diaktifkan.'

                : 'Position berhasil dinonaktifkan.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Pastikan position yang diakses benar-benar milik company yang login
    |--------------------------------------------------------------------------
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
