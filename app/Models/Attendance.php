<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Attendance extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'uuid',

        'company_id',

        'employee_id',

        'office_id',

        'assignment_id',

        'shift_id',

        'attendance_type',

        'attendance_date',

        'check_in_time',

        'check_out_time',

        'check_in_latitude',

        'check_in_longitude',

        'check_out_latitude',

        'check_out_longitude',

        'check_in_distance',

        'check_out_distance',

        'check_in_photo',

        'check_out_photo',

        'allowed_radius',

        'location_verified',

        'attendance_status',

        'is_checked_in',

        'is_checked_out',

        'late_minutes',

        'notes',

    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'attendance_date' => 'date',

        'check_in_time' => 'datetime:H:i:s',

        'check_out_time' => 'datetime:H:i:s',

        'check_in_distance' => 'float',

        'check_out_distance' => 'float',

        'allowed_radius' => 'integer',

        'location_verified' => 'boolean',

        'is_checked_in' => 'boolean',

        'is_checked_out' => 'boolean',

        'late_minutes' => 'integer',

    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Attendance $attendance) {

            if (blank($attendance->uuid)) {

                $attendance->uuid = (string) Str::uuid();

            }

        });
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
    | Employee
    |--------------------------------------------------------------------------
    */

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Office
    |--------------------------------------------------------------------------
    */

    public function office(): BelongsTo
    {
        return $this->belongsTo(
            Office::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment
    |--------------------------------------------------------------------------
    */

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(
            Assignment::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Shift
    |--------------------------------------------------------------------------
    */

    public function shift(): BelongsTo
    {
        return $this->belongsTo(
            Shift::class
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
    | Scope Today
    |--------------------------------------------------------------------------
    */

    public function scopeToday(
        Builder $query
    ): Builder {

        return $query->whereDate(
            'attendance_date',
            today()
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Office
    |--------------------------------------------------------------------------
    */

    public function scopeOffice(
        Builder $query
    ): Builder {

        return $query->where(
            'attendance_type',
            'OFFICE'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Assignment
    |--------------------------------------------------------------------------
    */

    public function scopeAssignment(
        Builder $query
    ): Builder {

        return $query->where(
            'attendance_type',
            'ASSIGNMENT'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Checked In
    |--------------------------------------------------------------------------
    */

    public function scopeCheckedIn(
        Builder $query
    ): Builder {

        return $query->where(
            'is_checked_in',
            true
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Checked Out
    |--------------------------------------------------------------------------
    */

    public function scopeCheckedOut(
        Builder $query
    ): Builder {

        return $query->where(
            'is_checked_out',
            true
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function isOfficeAttendance(): bool
    {
        return $this->attendance_type === 'OFFICE';
    }

    public function isAssignmentAttendance(): bool
    {
        return $this->attendance_type === 'ASSIGNMENT';
    }

    public function hasCheckedIn(): bool
    {
        return $this->is_checked_in;
    }

    public function hasCheckedOut(): bool
    {
        return $this->is_checked_out;
    }

    public function isLocationVerified(): bool
    {
        return $this->location_verified;
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Helper
    |--------------------------------------------------------------------------
    */

    public function canCheckIn(): bool
    {
        return ! $this->is_checked_in;
    }

    public function canCheckOut(): bool
    {
        return $this->is_checked_in
            && ! $this->is_checked_out;
    }

    public function isCompleted(): bool
    {
        return $this->is_checked_in
            && $this->is_checked_out;
    }
}