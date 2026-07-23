<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\Office;
use App\Models\Position;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EmployeeService extends BaseService
{
    /**
     * Get Employee List
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Employee::query()

            ->forCurrentCompany()

            ->with([

            'user.role',
            'currentEmployment.department',
            'currentEmployment.position',
            'currentEmployment.team',
            'currentEmployment.office',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('employee_number', 'ILIKE', "%{$search}%")
                    ->orWhere('full_name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%");

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Active Filter
        |--------------------------------------------------------------------------
        */

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {

            $query->where(
                'is_active',
                filter_var(
                    $filters['is_active'],
                    FILTER_VALIDATE_BOOLEAN
                )
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['department'])) {

            $query->whereHas(
                'currentEmployment.department',
                function ($q) use ($filters) {

                    $q->where(
                        'code',
                        $filters['department']
                    );

                }
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $filters['sort'] ?? 'full_name',
            $filters['direction'] ?? 'asc'
        );

        return $query->paginate(
            $filters['per_page'] ?? 10
        );
    }

    /**
     * Find Employee
     */
    public function find(int $id): ?Employee
    {
        return Employee::query()

        ->forCurrentCompany()

    ->with([
        'user.role',
        'currentEmployment.department',
        'currentEmployment.position',
        'currentEmployment.team',
        'currentEmployment.office',
    ])

    ->find($id);
    }

    /**
     * Assert Employee Quota Available
     *
     * Menolak pembuatan employee baru kalau jumlah employee aktif company
     * sudah mencapai batas max_employee sesuai subscription plan-nya
     * (Free / Premium Go / Premium Plus / Premium Max).
     */
    protected function assertEmployeeQuotaAvailable(?int $companyId): void
    {
        if (!$companyId) {
            return;
        }

        $company = Company::find($companyId);

        if (!$company) {
            return;
        }

        $currentCount = Employee::query()
            ->where('company_id', $companyId)
            ->count();

        if ($currentCount >= $company->max_employee) {

            throw ValidationException::withMessages([

                'employee_number' => "Jumlah karyawan sudah mencapai batas maksimal ({$company->max_employee}) untuk plan {$company->subscription_plan}. Silakan upgrade subscription untuk menambah karyawan.",

            ]);

        }
    }

    /**
     * Create Employee
     */
    public function create(array $data): Employee
    {
        $this->fillCompany($data);

        $this->assertEmployeeQuotaAvailable($data['company_id'] ?? null);

        return DB::transaction(function () use ($data) {
            /*
            |--------------------------------------------------------------------------
            | Upload Photo
            |--------------------------------------------------------------------------
            */

            $photo = $this->uploadPhoto(
                $data['photo'] ?? null
            );

            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            $this->fillCompany($data);

            /*
            |--------------------------------------------------------------------------
            | Identity Number (auto-generate jika kosong)
            |--------------------------------------------------------------------------
            */

            $identityNumber = $data['identity_number'] ?? null;

            if (blank($identityNumber)) {
                $identityNumber = $this->generateUniqueIdentityNumber();
            }

            $employee = Employee::create([

                'company_id' => $data['company_id'],

                'employee_number' => $data['employee_number'],

                'full_name' => $data['full_name'],

                'email' => $data['email'],

                'phone' => $data['phone'] ?? null,

                'gender' => $data['gender'],

                'birth_place' => $data['birth_place'] ?? null,

                'birth_date' => $data['birth_date'] ?? null,

                'address' => $data['address'] ?? null,

                'identity_number' => $identityNumber,

                'marital_status' => $data['marital_status'] ?? null,

                'photo' => $photo,

                'emergency_contact_name' =>
                    $data['emergency_contact_name'] ?? null,

                'emergency_contact_phone' =>
                    $data['emergency_contact_phone'] ?? null,

                'is_active' => filter_var(
                    $data['is_active'] ?? true,
                    FILTER_VALIDATE_BOOLEAN
                ),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Office (auto-assign ke Head Office company, bukan pilihan manual)
            |--------------------------------------------------------------------------
            */

            $officeId = $data['office_id']
                ?? $this->resolveDefaultOfficeId($data['company_id'] ?? null);

            if (!$officeId) {

                throw ValidationException::withMessages([

                    'office_id' => 'Company ini belum memiliki data Office. Silakan tambahkan Office terlebih dahulu di menu Office.',

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Employment History
            |--------------------------------------------------------------------------
            */

            EmploymentHistory::create([

                'employee_id' => $employee->id,

                'department_id' => $data['department_id'],

                'position_id' => $data['position_id'],

                'team_id' => $data['team_id'] ?? null,

                'office_id' => $officeId,

                'supervisor_id' => $data['supervisor_id'] ?? null,

                'employment_type' => $data['employment_type'],

                'employment_status' => $data['employment_status'],

                'start_date' => $data['start_date'],

                'is_current' => true,

            ]);

            /*
            |--------------------------------------------------------------------------
            | User Account
            |--------------------------------------------------------------------------
            */

            $employeeRoleId = Role::query()
                ->where('code', 'EMPLOYEE')
                ->value('id');

            User::create([
                'company_id'=>$data['company_id'],

                'employee_id' => $employee->id,

                'role_id' => $employeeRoleId,

                'username' => $data['username'],

                'email' => $data['user_email'] ?? $data['email'],

                'password' => Hash::make(
                    $data['password']
                ),

                'is_active' => filter_var(
                    $data['user_is_active'] ?? true,
                    FILTER_VALIDATE_BOOLEAN
                ),

            ]);

            return $employee->load([

                'user.role',

                'currentEmployment.department',

                'currentEmployment.position',

                'currentEmployment.team',

                'currentEmployment.office',

                ]);

        });
    }

    /**
     * Update Employee
     */
    public function update(
        Employee $employee,
        array $data
    ): Employee {
        $this->authorizeCompany($employee);

        /*
        |--------------------------------------------------------------------------
        | Guard: Employee harus milik Company yang sedang login
        |--------------------------------------------------------------------------
        */

        $this->authorizeCompany($employee);

        return DB::transaction(function () use (
            $employee,
            $data
        ) {
            /*
            |--------------------------------------------------------------------------
            | Upload New Photo
            |--------------------------------------------------------------------------
            */

            $photo = $employee->photo;

            if (!empty($data['photo'])) {

                $this->deletePhoto($employee->photo);

                $photo = $this->uploadPhoto(
                    $data['photo']
                );

            }
            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            $employee->update([

                'employee_number' => $data['employee_number'],

                'full_name' => $data['full_name'],

                'email' => $data['email'],

                'phone' => $data['phone'] ?? null,

                'photo' => $photo,

                'gender' => $data['gender'],

                'birth_place' => $data['birth_place'] ?? null,

                'birth_date' => $data['birth_date'] ?? null,

                'address' => $data['address'] ?? null,

                'identity_number' => $data['identity_number'] ?? null,

                'marital_status' => $data['marital_status'] ?? null,

                'emergency_contact_name' =>
                    $data['emergency_contact_name'] ?? null,

                'emergency_contact_phone' =>
                    $data['emergency_contact_phone'] ?? null,

                'is_active' => filter_var(
                    $data['is_active'] ?? true,
                    FILTER_VALIDATE_BOOLEAN
                ),


            ]);

            /*
            |--------------------------------------------------------------------------
            | Employment History
            |--------------------------------------------------------------------------
            */

            $employment = $employee->currentEmployment;

            if ($employment) {

                $employment->update([

                    'department_id' => $data['department_id'],

                    'position_id' => $data['position_id'],

                    'team_id' => $data['team_id'] ?? null,

                    'office_id' => $data['office_id'] ?? $employment->office_id,

                        'supervisor_id' => $data['supervisor_id'] ?? null,

                    'employment_type' => $data['employment_type'],

                    'employment_status' => $data['employment_status'],

                    'start_date' => $data['start_date'],

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | User Account
            |--------------------------------------------------------------------------
            */

            if ($employee->user) {

                // Role tidak boleh berubah dari halaman Employee.
                $userData = [

                    'username' => $data['username'],

                    'email' => $data['user_email'] ?? $data['email'],

                    'is_active' => filter_var(
                        $data['user_is_active'] ?? true,
                        FILTER_VALIDATE_BOOLEAN
                    ),

                ];

                /*
                |--------------------------------------------------------------------------
                | Password
                |--------------------------------------------------------------------------
                */

                if (!empty($data['password'])) {

                    $userData['password'] = Hash::make(
                        $data['password']
                    );

                }

                $employee->user->update($userData);

            }

            return $employee->fresh([

                'user.role',

                'currentEmployment.department',

                'currentEmployment.position',

                'currentEmployment.team',

                'currentEmployment.office',

                ]);

        });

    }

    public function delete(Employee $employee): bool
    {
        $this->authorizeCompany($employee);
        return DB::transaction(function () use ($employee) {

            /*
            |--------------------------------------------------------------------------
            | Delete Photo
            |--------------------------------------------------------------------------
            */

            $this->deletePhoto(
                $employee->photo
            );

            /*
            |--------------------------------------------------------------------------
            | Delete User
            |--------------------------------------------------------------------------
            */

            $employee->user()?->delete();

            /*
            |--------------------------------------------------------------------------
            | Delete Employment Histories
            |--------------------------------------------------------------------------
            */

            $employee->employmentHistories()->delete();

            /*
            |--------------------------------------------------------------------------
            | Soft Delete Employee
            |--------------------------------------------------------------------------
            */

            return (bool) $employee->delete();

        });
    }

    /**
     * Toggle Employee Active Status
     */
    public function toggleStatus(Employee $employee): Employee
    {
        $this->authorizeCompany($employee);

        $employee->update([

            'is_active' => !$employee->is_active,

        ]);

        return $employee->fresh();
    }

    /**
     * Data Create Form
     */
    public function createFormData(): array
    {
        return [

            'departments' => Department::forCurrentCompany()->orderBy('name')->get(),

            'positions' => Position::forCurrentCompany()->orderBy('name')->get(),

            'teams' => Team::forCurrentCompany()->orderBy('name')->get(),

            'employees' => Employee::query()

                ->forCurrentCompany()

                ->orderBy('full_name')

                ->get(),

        ];
    }

    /**
     * Resolve Default Office
     *
     * Karyawan tidak lagi memilih Office secara manual saat dibuat/diedit.
     * Office otomatis mengikuti Head Office milik company yang bersangkutan
     * (atau office pertama kalau belum ada yang ditandai sebagai Head Office).
     */
    private function resolveDefaultOfficeId(?int $companyId): ?int
    {
        if (!$companyId) {
            return null;
        }

        return Office::query()
            ->where('company_id', $companyId)
            ->orderByDesc('is_head_office')
            ->orderBy('name')
            ->value('id');
    }

    /**
     * Generate unique 9-digit Identity Number.
     */
    private function generateUniqueIdentityNumber(): string
    {
        do {
            $number = (string) random_int(100000000, 999999999);
        } while (
            Employee::query()
                ->where('identity_number', $number)
                ->exists()
        );

        return $number;
    }

    /**
     * Upload employee photo.
     */
    private function uploadPhoto(?UploadedFile $photo): ?string
    {
        if (!$photo) {
            return null;
        }

        return $photo->store(
            'employees',
            'public'
        );
    }

    /**
     * Delete employee photo.
     */
    private function deletePhoto(?string $photo): void
    {
        if (!$photo) {
            return;
        }

        if (Storage::disk('public')->exists($photo)) {

            Storage::disk('public')->delete($photo);

        }
    }
}