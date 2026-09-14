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

    public const STATUS_DRAFT = 'Draft';

    public const STATUS_ASSIGNED = 'Assigned';

    public const STATUS_IN_PROGRESS = 'In Progress';

    public const STATUS_COMPLETED = 'Completed';

    public const STATUS_CANCELLED = 'Cancelled';

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
        // Assignment list memakai aggregate counts supaya tidak perlu me-load
        // seluruh employee + pivot hanya untuk menentukan badge status.
        if (array_key_exists('assignment_employees_count', $this->attributes)) {
            $total = (int) ($this->assignment_employees_count ?? 0);
            $notWorked = (int) ($this->not_worked_employee_count ?? 0);
            $rejected = (int) ($this->rejected_employee_count ?? 0);
            $needsRevision = (int) ($this->needs_revision_employee_count ?? 0);
            $pendingReview = (int) ($this->pending_review_employee_count ?? 0);

            if ($total > 0 && $notWorked === $total) {
                return AssignmentEmployee::REVIEW_NOT_WORKED;
            }

            if ($total > 0 && $rejected === $total) {
                return AssignmentEmployee::STATUS_REJECTED;
            }

            // Needs Revision lebih actionable daripada Pending Review. Jika satu
            // assignment memiliki dua state sekaligus, tampilkan yang butuh aksi
            // employee terlebih dahulu agar Company tidak melewatkannya.
            if ($needsRevision > 0) {
                return AssignmentEmployee::REVIEW_NEEDS_REVISION;
            }

            if ($pendingReview > 0) {
                return AssignmentEmployee::REVIEW_PENDING;
            }

            return $this->status;
        }

        $employees = $this->relationLoaded('employees')
            ? $this->employees
            : $this->employees()->get();

        if ($employees->isNotEmpty() && $employees->every(
            fn ($employee) => in_array($employee->pivot->review_status, [AssignmentEmployee::REVIEW_NOT_WORKED, AssignmentEmployee::REVIEW_EXPIRED], true)
        )) {
            return AssignmentEmployee::REVIEW_NOT_WORKED;
        }

        if ($employees->isNotEmpty() && $employees->every(fn ($employee) => $employee->pivot->status === AssignmentEmployee::STATUS_REJECTED)) {
            return AssignmentEmployee::STATUS_REJECTED;
        }

        if ($employees->contains(fn ($employee) => $employee->pivot->review_status === AssignmentEmployee::REVIEW_NEEDS_REVISION)) {
            return AssignmentEmployee::REVIEW_NEEDS_REVISION;
        }

        if ($employees->contains(fn ($employee) => $employee->pivot->review_status === AssignmentEmployee::REVIEW_PENDING)) {
            return AssignmentEmployee::REVIEW_PENDING;
        }

        return $this->status;
    }

    public function rejectedEmployeeCount(): int
    {
        if (array_key_exists('rejected_employee_count', $this->attributes)) {
            return (int) $this->rejected_employee_count;
        }

        $employees = $this->relationLoaded('employees')
            ? $this->employees
            : $this->employees()->get();

        return $employees->filter(fn ($employee) => $employee->pivot->status === AssignmentEmployee::STATUS_REJECTED)->count();
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

                'work_check_in_at',

                'work_check_out_at',

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

    public function attachments(): HasMany
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

        if (! $user) {

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
            self::STATUS_DRAFT
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
            self::STATUS_ASSIGNED
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
            self::STATUS_IN_PROGRESS
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
            self::STATUS_COMPLETED
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
            self::STATUS_CANCELLED
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
        return $this->status === self::STATUS_DRAFT;
    }

    public function isAssigned(): bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
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
                self::STATUS_ASSIGNED,
                self::STATUS_IN_PROGRESS,
            ]
        );
    }

    public function isFinished(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ]
        );
    }

    public function canBeEdited(): bool
    {
        return ! $this->isFinished();
    }

    public function canBeDeleted(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function canAssignEmployee(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_DRAFT,
                self::STATUS_ASSIGNED,
            ]
        );
    }
}
