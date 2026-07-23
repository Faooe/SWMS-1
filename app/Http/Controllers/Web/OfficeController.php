<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfficeRequest;
use App\Models\Office;
use App\Services\OfficeService;

class OfficeController extends Controller
{
    public function __construct(
        protected OfficeService $officeService
    ) {
    }

    public function index()
    {
        return view('office.index');
    }

    public function edit(Office $office)
    {
        return view('office.edit', [
            'office' => $this->officeService->find($office->id),
        ]);
    }

    public function update(OfficeRequest $request, Office $office)
    {
        $this->officeService->update($office, $request->validated());

        return redirect()
            ->route('offices.edit', $office)
            ->with('success', 'Office berhasil diperbarui.');
    }
}