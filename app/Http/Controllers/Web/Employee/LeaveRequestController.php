<?php

namespace App\Http\Controllers\Web\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\StoreLeaveRequestRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{
    public function __construct(
        protected LeaveRequestService $leaveRequestService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | List Izin Milik Employee
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        $employee = Auth::user()->employee;

        return view('employee.leaves.index', [

            'leaves' => $this->leaveRequestService->getForEmployee(

                $employee,

                $request->only(['status', 'per_page'])

            ),

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Ajukan Izin
    |--------------------------------------------------------------------------
    */

    public function store(StoreLeaveRequestRequest $request)
    {

        $employee = Auth::user()->employee;

        try {

            $this->leaveRequestService->submit(

                $employee,

                $request->validated()

            );

        } catch (ValidationException $exception) {

            return back()

                ->withErrors($exception->errors())

                ->withInput();

        }

        return redirect()

            ->route('employee.leaves.index')

            ->with(
                'success',
                'Pengajuan izin berhasil dikirim, menunggu persetujuan admin.'
            );

    }
}
