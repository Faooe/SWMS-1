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
use App\Services\SecureFileService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
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
                'company',
                'user.role',
                'currentEmployment.department',
                'currentEmployment.position',
                'currentEmployment.team',
                'currentEmployment.office',
                'currentEmployment.shift',
                'currentEmployment.supervisor',
            ]);

        if (!empty($filters['search'])) {
            $search = trim((string) $filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'ILIKE', "%{$search}%")
                    ->orWhere('full_name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where(
                'is_active',
                filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN)
            );
        }

        if (!empty($filters['department'])) {
            $department = (string) $filters['department'];
            $query->whereHas('currentEmployment.department', function ($q) use ($department) {
                $q->where('code', $department);
            });
        }

        if (!empty($filters['office'])) {
            $office = (string) $filters['office'];
            $query->whereHas('currentEmployment.office', function ($q) use ($office) {
                $q->where('code', $office);
            });
        }

        $allowedSorts = ['full_name', 'employee_number', 'email', 'created_at'];
        $requestedSort = (string) ($filters['sort'] ?? 'full_name');
        $sort = in_array($requestedSort, $allowedSorts, true)
            ? $requestedSort
            : 'full_name';
        $direction = strtolower((string) ($filters['direction'] ?? 'asc')) === 'desc'
            ? 'desc'
            : 'asc';

        $query->orderBy($sort, $direction);

        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 10)));

        return $query->paginate($perPage);
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
        'company',
        'currentEmployment.department',
        'currentEmployment.position',
        'currentEmployment.team',
        'currentEmployment.office',
        'currentEmployment.shift',
        'currentEmployment.supervisor',
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
        $this->assertCompanyEmploymentReferences($data);

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

            $officeId = !empty($data['office_id'])
                ? (int) $data['office_id']
                : $this->resolveDefaultOfficeId($data['company_id'] ?? null);

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

                'is_active' => array_key_exists('user_is_active', $data)
                    ? filter_var($data['user_is_active'], FILTER_VALIDATE_BOOLEAN)
                    : $employee->is_active,

            ]);

            return $employee->load([

                'company',
                'user.role',

                'currentEmployment.department',

                'currentEmployment.position',

                'currentEmployment.team',

                'currentEmployment.office',
                'currentEmployment.shift',
                'currentEmployment.supervisor',

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
        $this->assertCompanyEmploymentReferences($data, $employee);

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

                'marital_status' => $data['marital_status'] ?? null,

                'emergency_contact_name' =>
                    $data['emergency_contact_name'] ?? null,

                'emergency_contact_phone' =>
                    $data['emergency_contact_phone'] ?? null,

                'is_active' => array_key_exists('is_active', $data)
                    ? filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN)
                    : $employee->is_active,

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

                    'office_id' => !empty($data['office_id'])
                        ? (int) $data['office_id']
                        : $employment->office_id,

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

                    'is_active' => array_key_exists('user_is_active', $data)
                        ? filter_var($data['user_is_active'], FILTER_VALIDATE_BOOLEAN)
                        : (array_key_exists('is_active', $data)
                            ? filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN)
                            : $employee->user->is_active),

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

                'company',
                'user.role',

                'currentEmployment.department',

                'currentEmployment.position',

                'currentEmployment.team',

                'currentEmployment.office',
                'currentEmployment.shift',
                'currentEmployment.supervisor',

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

        return DB::transaction(function () use ($employee) {
            $nextStatus = !$employee->is_active;

            $employee->update(['is_active' => $nextStatus]);
            $employee->user?->update(['is_active' => $nextStatus]);

            return $employee->fresh([
                'user.role',
                'company',
                'currentEmployment.department',
                'currentEmployment.position',
                'currentEmployment.team',
                'currentEmployment.office',
                'currentEmployment.shift',
                'currentEmployment.supervisor',
            ]);
        });
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
            'offices' => Office::forCurrentCompany()->orderByDesc('is_head_office')->orderBy('name')->get(),
            'employees' => Employee::query()
                ->forCurrentCompany()
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(),
        ];
    }

    /**
     * Pastikan semua master data yang dipilih benar-benar berasal dari company
     * yang sama. Rule `exists` di FormRequest saja tidak cukup untuk aplikasi
     * multi-tenant karena ID dari company lain tetap "exists".
     */
    private function assertCompanyEmploymentReferences(array $data, ?Employee $employee = null): void
    {
        $companyId = (int) ($employee?->company_id ?? $data['company_id'] ?? auth()->user()?->company_id ?? 0);

        if (!$companyId) {
            return;
        }

        $errors = [];

        $departmentId = (int) ($data['department_id'] ?? 0);
        if (!$departmentId || !Department::query()->where('company_id', $companyId)->whereKey($departmentId)->exists()) {
            $errors['department_id'] = 'Department tidak valid untuk company ini.';
        }

        $positionId = (int) ($data['position_id'] ?? 0);
        if (!$positionId || !Position::query()->where('company_id', $companyId)->whereKey($positionId)->exists()) {
            $errors['position_id'] = 'Position tidak valid untuk company ini.';
        }

        if (!empty($data['office_id']) && !Office::query()->where('company_id', $companyId)->whereKey($data['office_id'])->exists()) {
            $errors['office_id'] = 'Office tidak valid untuk company ini.';
        }

        if (!empty($data['team_id'])) {
            $team = Team::query()->where('company_id', $companyId)->find($data['team_id']);
            if (!$team) {
                $errors['team_id'] = 'Team tidak valid untuk company ini.';
            } elseif ($departmentId && (int) $team->department_id !== $departmentId) {
                $errors['team_id'] = 'Team harus berasal dari Department yang dipilih.';
            }
        }

        if (!empty($data['supervisor_id'])) {
            $supervisorId = (int) $data['supervisor_id'];
            if ($employee && $supervisorId === (int) $employee->id) {
                $errors['supervisor_id'] = 'Employee tidak dapat menjadi supervisor untuk dirinya sendiri.';
            } elseif (!Employee::query()->where('company_id', $companyId)->where('is_active', true)->whereKey($supervisorId)->exists()) {
                $errors['supervisor_id'] = 'Supervisor tidak valid untuk company ini.';
            }
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
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
     * Upload employee photo.
     */
    private function uploadPhoto(?UploadedFile $photo): ?string
    {
        if (!$photo) {
            return null;
        }

        return app(SecureFileService::class)->store(
            $photo,
            'employees'
        );
    }

    /**
     * Delete employee photo.
     */
    private function deletePhoto(?string $photo): void
    {
        app(SecureFileService::class)->delete($photo);
    }
}