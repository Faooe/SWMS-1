<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\Office;
use App\Services\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct(
        protected AssignmentService $assignmentService
    ) {
    }

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

                'Assignment created successfully.'

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

                'Assignment updated successfully.'

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

                'Assignment deleted successfully.'

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

        } catch (\Illuminate\Validation\ValidationException $exception) {

            return back()->withErrors($exception->errors());

        }

        return back()->with('success', 'Hasil kerja berhasil disetujui.');

    }

    public function rejectCompletion(
        \App\Http\Requests\Assignment\RejectCompletionRequest $request,
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

        } catch (\Illuminate\Validation\ValidationException $exception) {

            return back()->withErrors($exception->errors());

        }

        return back()->with('success', 'Hasil kerja ditolak, employee akan diminta revisi.');

    }

    public function approveCheckoutCorrection(
        \Illuminate\Http\Request $request,
        Assignment $assignment,
        \App\Models\AttendanceCheckoutCorrection $correction,
        \App\Services\AttendanceCheckoutCorrectionService $correctionService
    ) {
        try { $correctionService->approve($request->user(), $assignment, $correction, $request->input('review_notes')); }
        catch (\Illuminate\Validation\ValidationException $e) { return back()->withErrors($e->errors()); }
        return back()->with('success', 'Koreksi Check Out disetujui.');
    }

    public function rejectCheckoutCorrection(
        \Illuminate\Http\Request $request,
        Assignment $assignment,
        \App\Models\AttendanceCheckoutCorrection $correction,
        \App\Services\AttendanceCheckoutCorrectionService $correctionService
    ) {
        $request->validate(['review_notes'=>['nullable','string','max:1000']]);
        try { $correctionService->reject($request->user(), $assignment, $correction, $request->input('review_notes')); }
        catch (\Illuminate\Validation\ValidationException $e) { return back()->withErrors($e->errors()); }
        return back()->with('success', 'Koreksi Check Out ditolak.');
    }

}