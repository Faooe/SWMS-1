<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'uuid',

        'company_id',

        'employee_number',

        'full_name',

        'email',

        'phone',

        'gender',

        'birth_place',

        'birth_date',

        'address',

        'identity_number',

        'marital_status',

        'photo',

        'emergency_contact_name',

        'emergency_contact_phone',

        'is_active',

    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'birth_date' => 'date',

        'is_active' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Employee $employee) {

            if (blank($employee->uuid)) {

                $employee->uuid = (string) Str::uuid();

            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scope Active
    |--------------------------------------------------------------------------
    */

    public function scopeActive(
        Builder $query
    ): Builder {

        return $query->where(
            'is_active',
            true
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Current Company
    |--------------------------------------------------------------------------
    */

    public function scopeForCurrentCompany(
        Builder $query
    ): Builder {

        $user = Auth::user();

        if (!$user) {

            return $query;

        }

        if ($user->company_id) {

            $query->where(
                'company_id',
                $user->company_id
            );

        }

        return $query;

    }

    /*
    |--------------------------------------------------------------------------
    | Company
    |--------------------------------------------------------------------------
    */

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            Company::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user(): HasOne
    {
        return $this->hasOne(
            User::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employment Histories
    |--------------------------------------------------------------------------
    */

    public function employmentHistories(): HasMany
    {
        return $this->hasMany(
            EmploymentHistory::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Current Employment
    |--------------------------------------------------------------------------
    */

    public function currentEmployment(): HasOne
    {
        return $this->hasOne(
            EmploymentHistory::class
        )->where(
            'is_current',
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Attendances
    |--------------------------------------------------------------------------
    */

    public function attendances(): HasMany
    {
        return $this->hasMany(
            Attendance::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Today's Attendance
    |--------------------------------------------------------------------------
    */

    public function todayAttendance(): HasOne
    {
        return $this->hasOne(
            Attendance::class
        )->whereDate(
            'attendance_date',
            today()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Requests (Permission / Izin)
    |--------------------------------------------------------------------------
    */

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(
            LeaveRequest::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Assignments
    |--------------------------------------------------------------------------
    */

    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(

            Assignment::class,

            'assignment_employees'

        )

        ->withPivot([

            'status',

            'assigned_at',

            'accepted_at',

            'started_at',

            'finished_at',

            'notes',

        ])

        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Employees
    |--------------------------------------------------------------------------
    */

    public function assignmentEmployees(): HasMany
    {
        return $this->hasMany(
            AssignmentEmployee::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Logs
    |--------------------------------------------------------------------------
    */

    public function assignmentLogs(): HasMany
    {
        return $this->hasMany(
            AssignmentLog::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Current Assignment
    |--------------------------------------------------------------------------
    */

    public function getCurrentAssignmentAttribute(): ?AssignmentEmployee
    {
        return $this->assignmentEmployees()

            ->whereIn(
                'status',
                [
                    'Assigned',
                    'Accepted',
                    'In Progress',
                ]
            )

            ->latest()

            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Current Attendance
    |--------------------------------------------------------------------------
    */

    public function getCurrentAttendanceAttribute(): ?Attendance
    {
        return $this->todayAttendance()->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Checked In Today
    |--------------------------------------------------------------------------
    */

    public function getHasCheckedInTodayAttribute(): bool
    {
        return $this->todayAttendance()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Busy Status
    |--------------------------------------------------------------------------
    */

    public function getIsBusyAttribute(): bool
    {
        return $this->currentAssignment !== null;
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Status
    |--------------------------------------------------------------------------
    */

    public function getAttendanceStatusAttribute(): string
    {
        if (!$this->currentAttendance) {

            return 'Not Checked In';

        }

        if (

            $this->currentAttendance->is_checked_in &&

            !$this->currentAttendance->is_checked_out

        ) {

            return 'Working';

        }

        if (

            $this->currentAttendance->is_checked_out

        ) {

            return 'Finished';

        }

        return 'Unknown';
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function isAvailable(): bool
    {
        return !$this->isBusy;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function hasCurrentAssignment(): bool
    {
        return $this->currentAssignment !== null;
    }

    public function hasCheckedIn(): bool
    {
        return $this->has_checked_in_today;
    }
}