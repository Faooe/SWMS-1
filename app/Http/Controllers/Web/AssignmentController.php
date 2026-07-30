<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
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

            'sort' => $request->sort,

            'direction' => $request->direction,

            'per_page' => $request->per_page ?? 10,

        ];

        return view(

            'assignment.index',

            [

                'assignments' => $this->assignmentService
                    ->getAll($filters),

                'statistics' => [

                    'total' => Assignment::query()

                        ->forCurrentCompany()

                        ->count(),

                    'draft' => Assignment::query()

                        ->forCurrentCompany()

                        ->draft()

                        ->count(),

                    'active' => Assignment::query()

                        ->forCurrentCompany()

                        ->active()

                        ->count(),

                    'completed' => Assignment::query()

                        ->forCurrentCompany()

                        ->completed()

                        ->count(),

                ],

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
}