<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{
    public function __construct(
        protected LeaveRequestService $leaveRequestService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | List Semua Pengajuan Izin
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        return view('leaves.index', [

            'leaves' => $this->leaveRequestService->getAll(

                $request->only(['search', 'status', 'per_page'])

            ),

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Approve Izin
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        LeaveRequest $leave
    ) {

        try {

            $this->leaveRequestService->approve(

                $leave,

                $request->user()

            );

        } catch (ValidationException $exception) {

            return back()->with(
                'error',
                collect($exception->errors())->flatten()->first()
            );

        }

        return back()->with(
            'success',
            'Pengajuan izin berhasil disetujui.'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Reject Izin
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        LeaveRequest $leave
    ) {

        $request->validate([

            'rejection_reason' => ['nullable', 'string', 'max:1000'],

        ]);

        try {

            $this->leaveRequestService->reject(

                $leave,

                $request->user(),

                $request->rejection_reason

            );

        } catch (ValidationException $exception) {

            return back()->with(
                'error',
                collect($exception->errors())->flatten()->first()
            );

        }

        return back()->with(
            'success',
            'Pengajuan izin ditolak.'
        );

    }
}
