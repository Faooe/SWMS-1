<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {
    }

    /**
     * Display Employee List
     */
    public function index(Request $request)
    {
        $filters = [

            'search'     => $request->search,

            'department' => $request->department,

            'is_active'  => $request->is_active,

            'sort'       => $request->sort,

            'direction'  => $request->direction,

            'per_page'   => $request->per_page ?? 10,

        ];

        return view('employee.index', [

            /*
            |--------------------------------------------------------------------------
            | Employee Data
            |--------------------------------------------------------------------------
            */

            'employees' => $this->employeeService->getAll($filters),

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'statistics' => [

                'total' => Employee::query()

                    ->forCurrentCompany()

                    ->count(),

                'active' => Employee::query()

                    ->forCurrentCompany()

                    ->where('is_active', true)

                    ->count(),

                'inactive' => Employee::query()

                    ->forCurrentCompany()

                    ->where('is_active', false)

                    ->count(),

                'new_this_month' => Employee::query()

                    ->forCurrentCompany()

                    ->whereMonth('created_at', now()->month)

                    ->count(),

            ],

            /*
            |--------------------------------------------------------------------------
            | Filter Data
            |--------------------------------------------------------------------------
            */

            'departments' => Department::forCurrentCompany()->orderBy('name')->get(),

        ]);
    }

    /**
     * Show Create Employee Form
     */
    public function create()
    {
        return view(

            'employee.create',

            $this->employeeService->createFormData()

        );
    }

    /**
     * Store Employee
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = $this->employeeService->create(

            $request->validated()

        );

        return redirect()

            ->route('employees.show', $employee)

            ->with(

                'success',

                'Employee berhasil dibuat.'

            );
    }

    /**
     * Employee Detail
     */
    public function show(Employee $employee)
    {
        return view(

            'employee.show',

            [

                'employee' => $this->employeeService->find(
                    $employee->id
                ),

            ]

        );
    }

    /**
     * Show Edit Employee Form
     */
    public function edit(Employee $employee)
    {
        return view(

            'employee.edit',

            array_merge(

                $this->employeeService->createFormData(),

                [

                    'employee' => $this->employeeService->find(
                        $employee->id
                    ),

                ]

            )

        );
    }

    /**
     * Update Employee
     */
    public function update(
        UpdateEmployeeRequest $request,
        Employee $employee
    ) {

        $this->employeeService->update(

            $employee,

            $request->validated()

        );

        return redirect()

            ->route('employees.show', $employee)

            ->with(

                'success',

                'Employee berhasil diperbarui.'

            );

    }

    /**
     * Delete Employee
     */
    public function destroy(Employee $employee)
    {
        $this->employeeService->delete($employee);

        return redirect()

            ->route('employees.index')

            ->with(

                'success',

                'Employee berhasil dihapus.'

            );
    }

    /**
     * Toggle Employee Status
     */
    public function toggleStatus(Employee $employee)
    {
        $employee = $this->employeeService->toggleStatus($employee);

        return back()->with(

            'success',

            $employee->is_active

                ? 'Employee berhasil diaktifkan.'

                : 'Employee berhasil dinonaktifkan.'

        );
    }
}