<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentEmployee extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'assignment_id',

        'employee_id',

        'status',

        'assigned_at',

        'accepted_at',

        'started_at',

        'finished_at',

        'notes',
        'completion_photo',
        'completion_photo_2',
        'completion_notes',
        'review_status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
        'revision_deadline_at',
        'is_late_revision',
        'revision_count',

    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'assigned_at' => 'datetime',

        'accepted_at' => 'datetime',

        'started_at' => 'datetime',

        'finished_at' => 'datetime',

        'reviewed_at' => 'datetime',

        'revision_deadline_at' => 'datetime',

        'is_late_revision' => 'boolean',

        'revision_count' => 'integer',

    ];

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
    | Query Scope
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

    public function scopeAccepted(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'Accepted'
        );

    }

    public function scopeInProgress(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'In Progress'
        );

    }

    public function scopeCompleted(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'Completed'
        );

    }

    public function scopeRejected(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'Rejected'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Helper Status
    |--------------------------------------------------------------------------
    */

    public function isAssigned(): bool
    {
        return $this->status === 'Assigned';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'Accepted';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'In Progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Workflow
    |--------------------------------------------------------------------------
    */

    /**
     * Assignment masih bisa diterima?
     */
    public function canBeAccepted(): bool
    {
        return $this->isAssigned();
    }

    /**
     * Assignment sudah boleh Check In?
     */
    public function canCheckIn(): bool
    {
        return $this->isAccepted();
    }

    /**
     * Assignment sudah boleh Check Out?
     */
    public function canCheckOut(): bool
    {
        return $this->isInProgress();
    }

    /**
     * Assignment sudah selesai?
     */
    public function isFinished(): bool
    {
        return $this->isCompleted();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Review (approve/reject hasil kerja oleh company)
    |--------------------------------------------------------------------------
    |
    | review_status TERPISAH dari `status` di atas -- lihat komentar di
    | migration 2026_08_12_090000_add_review_fields_to_assignment_employees_table.
    |
    */

    public function isPendingReview(): bool
    {
        return $this->review_status === 'Pending Review';
    }

    public function isApproved(): bool
    {
        return $this->review_status === 'Approved';
    }

    public function needsRevision(): bool
    {
        return $this->review_status === 'Needs Revision';
    }

    public function isRevisionExpired(): bool
    {
        return $this->review_status === 'Expired';
    }

    /**
     * Employee masih boleh submit/resubmit hasil kerja?
     *
     * Boleh kalau: assignment sedang berjalan & belum pernah submit sama
     * sekali (review_status masih null), ATAU company sudah reject
     * (Needs Revision) dan batas waktu revisi belum "Expired".
     */
    public function canSubmitCompletion(): bool
    {
        if ($this->review_status === null) {
            return true;
        }

        return $this->needsRevision();
    }

    /**
     * Batas waktu revisi (revision_deadline_at) + toleransi telat 30
     * menit sudah kelewat? Kalau ya, resubmit sudah tidak boleh lagi
     * (harus nunggu di-flip 'Expired' oleh scheduled job).
     */
    public function isPastRevisionGracePeriod(): bool
    {
        if (!$this->revision_deadline_at) {
            return false;
        }

        return now()->greaterThan(
            $this->revision_deadline_at->copy()->addMinutes(30)
        );
    }

    /**
     * Resubmit sekarang bakal kena tandai "Late Pengerjaan"? (lewat
     * deadline, tapi masih dalam toleransi 30 menit).
     */
    public function isWithinLateRevisionGrace(): bool
    {
        if (!$this->revision_deadline_at) {
            return false;
        }

        $now = now();

        return $now->greaterThan($this->revision_deadline_at)
            && $now->lessThanOrEqualTo($this->revision_deadline_at->copy()->addMinutes(30));
    }
}