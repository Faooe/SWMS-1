<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Company List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('platform.company.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->authorize(
            'create',
            Company::class
        );

        return view(
            'platform.company.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        CompanyRequest $request
    ) {
        $this->authorize(
            'create',
            Company::class
        );

        $result = $this->companyService->create(

            $request->validated()

        );

        return redirect()

            ->route(

                'platform.companies.show',

                $result['company']

            )

            ->with([

                'success' => 'Company berhasil dibuat.',

                'generated_username' => $result['username'],

                'generated_email' => $result['email'],

                'generated_password' => $result['password'],

            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    public function show(
        Company $company
    ) {
        $this->authorize(
            'view',
            $company
        );

        return view(

            'platform.company.show',

            [

                'company' => $this->companyService->find(
                    $company->id
                ),

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Company $company
    ) {
        $this->authorize(
            'update',
            $company
        );

        // Sebelumnya form edit selalu nampilin field Super Admin kosong
        // karena $company->admin_email memang tidak pernah ada. Sekarang
        // ambil user Super Admin yang sesungguhnya supaya form nampilin
        // email/username yang beneran dipakai untuk login saat ini.
        $superAdmin = $company->users()

            ->whereHas(
                'role',
                fn ($q) => $q->where('code', 'SUPER_ADMIN')
            )

            ->first();

        return view(

            'platform.company.edit',

            compact('company', 'superAdmin')

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(

        CompanyRequest $request,

        Company $company

    ) {
        $this->authorize(
            'update',
            $company
        );

        $company = $this->companyService->update(

            $company,

            $request->validated()

        );

        return redirect()

            ->route(

                'platform.companies.show',

                $company

            )

            ->with(

                'success',

                'Company berhasil diperbarui.'

            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Company $company
    ) {
        $this->authorize(
            'delete',
            $company
        );

        $this->companyService->delete(

            $company

        );

        return redirect()

            ->route(

                'platform.companies.index'

            )

            ->with(

                'success',

                'Company berhasil dihapus.'

            );
    }
    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(
        Company $company
    ) {
        $this->authorize(
            'update',
            $company
        );

        $company = $this->companyService->toggleStatus(

            $company

        );

        return back()->with(

            'success',

            $company->is_active

                ? 'Company berhasil diaktifkan.'

                : 'Company berhasil dinonaktifkan.'

        );
    }
}