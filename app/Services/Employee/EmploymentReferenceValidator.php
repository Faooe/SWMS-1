<?php

namespace App\Services\Employee;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Position;
use App\Models\Team;
use Illuminate\Validation\ValidationException;

class EmploymentReferenceValidator
{
    /**
     * Ensure every selected master-data record belongs to the employee's
     * company. An unscoped FormRequest exists rule is not sufficient here.
     */
    public function assertValid(array $data, ?Employee $employee = null): void
    {
        $companyId = (int) ($employee?->company_id ?? $data['company_id'] ?? auth()->user()?->company_id ?? 0);

        if (! $companyId) {
            return;
        }

        $errors = [];

        $departmentId = (int) ($data['department_id'] ?? 0);
        if (! $departmentId || ! Department::query()->where('company_id', $companyId)->whereKey($departmentId)->exists()) {
            $errors['department_id'] = 'Department tidak valid untuk company ini.';
        }

        $positionId = (int) ($data['position_id'] ?? 0);
        if (! $positionId || ! Position::query()->where('company_id', $companyId)->whereKey($positionId)->exists()) {
            $errors['position_id'] = 'Position tidak valid untuk company ini.';
        }

        if (! empty($data['office_id']) && ! Office::query()->where('company_id', $companyId)->whereKey($data['office_id'])->exists()) {
            $errors['office_id'] = 'Office tidak valid untuk company ini.';
        }

        if (! empty($data['team_id'])) {
            $team = Team::query()->where('company_id', $companyId)->find($data['team_id']);
            if (! $team) {
                $errors['team_id'] = 'Team tidak valid untuk company ini.';
            } elseif ($departmentId && (int) $team->department_id !== $departmentId) {
                $errors['team_id'] = 'Team harus berasal dari Department yang dipilih.';
            }
        }

        if (! empty($data['supervisor_id'])) {
            $supervisorId = (int) $data['supervisor_id'];
            if ($employee && $supervisorId === (int) $employee->id) {
                $errors['supervisor_id'] = 'Employee tidak dapat menjadi supervisor untuk dirinya sendiri.';
            } elseif (! Employee::query()->where('company_id', $companyId)->where('is_active', true)->whereKey($supervisorId)->exists()) {
                $errors['supervisor_id'] = 'Supervisor tidak valid untuk company ini.';
            }
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
    }
}
