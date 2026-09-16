<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assignment\RejectCompletionRequest;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\AttendanceCheckoutCorrection;
use App\Models\Office;
use App\Services\AssignmentService;
use App\Services\AttendanceCheckoutCorrectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function __construct(
        protected AssignmentService $assignmentService
    ) {}

    /**
     * Assignment List
     */
    public function index(Request $request)
    {
        $filters = [

            'search' => $request->search,

            'office' => $request->office,

            'priority' => $request->priority,

            'status' => $request->status,

            'date' => $request->date,

            'sort' => $request->sort,

            'direction' => $request->direction,

            'per_page' => $request->per_page ?? 10,

        ];

        return view(

            'assignment.index',

            [

                'assignments' => $this->assignmentService
                    ->getAll($filters),

                'statistics' => $this->assignmentService->companyStatistics(),

                'offices' => Office::query()
                    ->forCurrentCompany()
                    ->orderBy('name')
                    ->get(),

            ]

        );
    }

    /**
     * Create Form
     */
    public function create()
    {
        return view(

            'assignment.create',

            $this->assignmentService
                ->createFormData()

        );
    }

    /**
     * Store
     */
    public function store(
        StoreAssignmentRequest $request
    ) {

        $assignment = $this->assignmentService->create(

            $request->validated(),

            Auth::id()

        );

        return redirect()

            ->route(

                'assignments.show',

                $assignment

            )

            ->with(

                'success',

                'Assignment berhasil dibuat.'

            );

    }

    /**
     * Detail
     */
    public function show(
        Assignment $assignment
    ) {

        return view(

            'assignment.show',

            [

                'assignment' => $this->assignmentService
                    ->find($assignment->id),

            ]

        );

    }

    /**
     * Edit
     */
    public function edit(
        Assignment $assignment
    ) {

        return view(

            'assignment.edit',

            array_merge(

                $this->assignmentService
                    ->createFormData(),

                [

                    'assignment' => $this->assignmentService
                        ->find($assignment->id),

                ]

            )

        );

    }

    /**
     * Update
     */
    public function update(

        UpdateAssignmentRequest $request,

        Assignment $assignment

    ) {

        $this->assignmentService->update(

            $assignment,

            $request->validated()

        );

        return redirect()

            ->route(

                'assignments.show',

                $assignment

            )

            ->with(

                'success',

                'Assignment berhasil diperbarui.'

            );

    }

    /**
     * Delete
     */
    public function destroy(
        Assignment $assignment
    ) {

        $this->assignmentService
            ->delete($assignment);

        return redirect()

            ->route(

                'assignments.index'

            )

            ->with(

                'success',

                'Assignment berhasil dihapus.'

            );

    }

    /*
    |--------------------------------------------------------------------------
    | Review Hasil Kerja (Approve / Reject)
    |--------------------------------------------------------------------------
    */

    public function approveCompletion(Assignment $assignment, int $employeeId)
    {

        try {

            $this->assignmentService->approveCompletion(
                $assignment,
                $employeeId,
                Auth::id()
            );

        } catch (ValidationException $exception) {

            return back()->withErrors($exception->errors());

        }

        return back()->with('success', 'Hasil kerja berhasil disetujui.');

    }

    public function rejectCompletion(
        RejectCompletionRequest $request,
        Assignment $assignment,
        int $employeeId
    ) {

        try {

            $this->assignmentService->rejectCompletion(
                $assignment,
                $employeeId,
                Auth::id(),
                $request->validated('review_notes'),
                $request->validated('revision_minutes')
            );

        } catch (ValidationException $exception) {

            return back()->withErrors($exception->errors());

        }

        return back()->with('success', 'Hasil kerja ditolak, employee akan diminta revisi.');

    }

    public function approveCheckoutCorrection(
        Request $request,
        Assignment $assignment,
        AttendanceCheckoutCorrection $correction,
        AttendanceCheckoutCorrectionService $correctionService
    ) {
        try {
            $correctionService->approve($request->user(), $assignment, $correction, $request->input('review_notes'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Koreksi Check Out disetujui.');
    }

    public function rejectCheckoutCorrection(
        Request $request,
        Assignment $assignment,
        AttendanceCheckoutCorrection $correction,
        AttendanceCheckoutCorrectionService $correctionService
    ) {
        $request->validate(['review_notes' => ['nullable', 'string', 'max:1000']]);
        try {
            $correctionService->reject($request->user(), $assignment, $correction, $request->input('review_notes'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Koreksi Check Out ditolak.');
    }
}
