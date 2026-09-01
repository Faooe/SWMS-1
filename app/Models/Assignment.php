<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Assignment extends Model
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

        'assignment_number',

        'title',

        'description',

        'office_id',

        'location_name',

        'address',

        'latitude',

        'longitude',

        'radius',

        'polygon',

        'priority',

        'assignment_type',

        'status',

        'start_datetime',

        'end_datetime',

        'daily_attendance_enabled',

        'attendance_day_rule',

        'created_by',

    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'latitude' => 'float',

        'longitude' => 'float',

        'radius' => 'integer',

        'polygon' => 'array',

        'start_datetime' => 'datetime',

        'end_datetime' => 'datetime',

        'daily_attendance_enabled' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Appended
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'employee_count',

    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Assignment $assignment) {

            if (blank($assignment->uuid)) {

                $assignment->uuid = (string) Str::uuid();

            }

        });
    }

    /**
     * Status yang ditampilkan ke Company Admin. Status global assignment tidak
     * diubah hanya karena satu employee menolak. Jika SEMUA employee menolak,
     * assignment ditampilkan sebagai Rejected. Untuk mixed state, status global
     * tetap dipakai dan jumlah rejected tersedia sebagai data pendamping.
     */
    public function companyDisplayStatus(): string
    {
        $employees = $this->relationLoaded('employees')
            ? $this->employees
            : $this->employees()->get();

        if ($employees->isNotEmpty() && $employees->every(
            fn ($employee) => in_array($employee->pivot->review_status, ['Not Worked', 'Expired'], true)
        )) {
            return 'Not Worked';
        }

        if ($employees->isNotEmpty() && $employees->every(fn ($employee) => $employee->pivot->status === 'Rejected')) {
            return 'Rejected';
        }

        if ($employees->contains(fn ($employee) => $employee->pivot->review_status === 'Pending Review')) {
            return 'Pending Review';
        }

        if ($employees->contains(fn ($employee) => $employee->pivot->review_status === 'Needs Revision')) {
            return 'Needs Revision';
        }

        return $this->status;
    }

    public function rejectedEmployeeCount(): int
    {
        $employees = $this->relationLoaded('employees')
            ? $this->employees
            : $this->employees()->get();

        return $employees->filter(fn ($employee) => $employee->pivot->status === 'Rejected')->count();
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
    | Creator
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employees
    |--------------------------------------------------------------------------
    */

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(

            Employee::class,

            'assignment_employees'

        )

        // ->using() PENTING -- tanpa ini, $employee->pivot cuma jadi
        // instance generic Illuminate\Database\Eloquent\Relations\Pivot,
        // BUKAN AssignmentEmployee, sehingga method custom di model itu
        // (needsRevision(), isPastRevisionGracePeriod(), dst -- dipakai
        // AssignmentResource buat hitung 'my_actions.can_resubmit') akan
        // error "Call to undefined method Pivot::needsRevision()".
        ->using(AssignmentEmployee::class)

        ->withPivot([

            'status',

            'assigned_at',

            'accepted_at',

            'started_at',

            'finished_at',

            'notes',

            'completion_photo',

            // Kolom review (migration 2026_08_12_090000) -- tanpa
            // di-daftarkan di sini, field-field ini SELALU null di
            // $pivot walau datanya ada di tabel assignment_employees,
            // karena withPivot() menentukan kolom mana saja yang
            // di-load ke object pivot.
            'completion_photo_2',

            'completion_notes',

            'review_status',

            'review_notes',

            'reviewed_by',

            'reviewed_at',

            'revision_deadline_at',

            'is_late_revision',

            'revision_count',

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
    | Logs
    |--------------------------------------------------------------------------
    */

    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AssignmentAttachment::class)->orderBy('created_at');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(
            AssignmentLog::class
        )->orderBy('created_at')->orderBy('id');
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
    | Scope Draft
    |--------------------------------------------------------------------------
    */

    public function scopeDraft(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'Draft'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Assigned
    |--------------------------------------------------------------------------
    */

    public function scopeAssigned(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'Assigned'
        );

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
            'status',
            'In Progress'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Completed
    |--------------------------------------------------------------------------
    */

    public function scopeCompleted(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'Completed'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Cancelled
    |--------------------------------------------------------------------------
    */

    public function scopeCancelled(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'Cancelled'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getEmployeeCountAttribute(): int
    {
        return $this->assignmentEmployees_count

            ?? $this->assignmentEmployees()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helper
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }

    public function isAssigned(): bool
    {
        return $this->status === 'Assigned';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'In Progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'Cancelled';
    }

    /*
    |--------------------------------------------------------------------------
    | Workflow Helper
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return in_array(
            $this->status,
            [
                'Assigned',
                'In Progress',
            ]
        );
    }

    public function isFinished(): bool
    {
        return in_array(
            $this->status,
            [
                'Completed',
                'Cancelled',
            ]
        );
    }

    public function canBeEdited(): bool
    {
        return ! $this->isFinished();
    }

    public function canBeDeleted(): bool
    {
        return $this->status === 'Draft';
    }

    public function canAssignEmployee(): bool
    {
        return in_array(
            $this->status,
            [
                'Draft',
                'Assigned',
            ]
        );
    }
}