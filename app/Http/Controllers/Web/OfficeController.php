<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfficeRequest;
use App\Models\Office;
use App\Services\OfficeService;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function __construct(
        protected OfficeService $officeService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Office List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        return view('office.index', [

            'offices' => $this->officeService->getOffices(
                $request->only([
                    'search',
                    'province',
                    'city',
                    'status',
                    'per_page',
                ])
            ),

            'statistics' => $this->officeService->statistics(),

            'provinces' => $this->officeService->provinces(),

            'cities' => $this->officeService->cities(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('office.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(OfficeRequest $request)
    {
        $this->officeService->store(
            $request->validated()
        );

        return redirect()

            ->route('offices.index')

            ->with(
                'success',
                'Office berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    public function show(Office $office)
    {
        return view('office.show', [

            'office' => $this->officeService->find(
                $office->id
            ),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Office $office)
    {
        return view('office.edit', [

            'office' => $office,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        OfficeRequest $request,
        Office $office
    ) {

        $this->officeService->update(

            $office,

            $request->validated()

        );

        return redirect()

            ->route('offices.index')

            ->with(
                'success',
                'Office berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Office $office
    ) {

        $this->officeService->destroy(
            $office
        );

        return redirect()

            ->route('offices.index')

            ->with(
                'success',
                'Office berhasil dihapus.'
            );
    }
}