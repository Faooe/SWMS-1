<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * WAJIB extends Pivot (bukan Model biasa) -- ini yang dipasang lewat
 * Assignment::employees()/Employee::assignments() via ->using(). Kalau
 * cuma extends Model, BelongsToMany bakal error "Call to undefined
 * method AssignmentEmployee::fromRawAttributes()" begitu relasi
 * di-load, karena method itu (dan beberapa method internal lain yang
 * dipakai proses hydrate pivot) cuma ada di class Pivot, tidak ada di
 * Model biasa.
 *
 * Tabel assignment_employees punya kolom 'id' auto-increment biasa
 * (bukan composite key gaya pivot standar Laravel) -- makanya
 * $incrementing & $primaryKey di-override manual, karena Pivot secara
 * default menganggap tabel pivot TIDAK punya auto-increment id sendiri.
 * Tanpa override ini, method sepert save()/fresh() di baris yang sudah
 * ada bisa salah target row.
 */
class AssignmentEmployee extends Pivot
{
    use HasFactory;

    protected $table = 'assignment_employees';

    public $incrementing = true;

    protected $primaryKey = 'id';

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
     * Lewat revision_deadline_at sejauh ini (menit) -> resubmit ditandai
     * "Late Pengerjaan". Di bawah ini masih dianggap tepat waktu.
     */
    public const LATE_REVISION_THRESHOLD_MINUTES = 30;

    /**
     * Lewat revision_deadline_at sejauh ini (menit) -> resubmit sudah
     * tidak boleh sama sekali (bakal di-flip 'Expired' oleh scheduled
     * job). 2 jam = 120 menit.
     */
    public const REVISION_BLOCK_THRESHOLD_MINUTES = 120;

    /**
     * Batas waktu revisi (revision_deadline_at) + toleransi 2 jam sudah
     * kelewat? Kalau ya, resubmit sudah tidak boleh lagi (harus nunggu
     * di-flip 'Expired' oleh scheduled job).
     */
    public function isPastRevisionGracePeriod(): bool
    {
        if (!$this->revision_deadline_at) {
            return false;
        }

        return now()->greaterThan(
            $this->revision_deadline_at->copy()->addMinutes(self::REVISION_BLOCK_THRESHOLD_MINUTES)
        );
    }

    /**
     * Resubmit sekarang bakal kena tandai "Late Pengerjaan"? (sudah
     * lewat deadline lebih dari 30 menit, tapi belum lewat 2 jam --
     * masih boleh resubmit, cuma ditandai telat).
     */
    public function isWithinLateRevisionGrace(): bool
    {
        if (!$this->revision_deadline_at) {
            return false;
        }

        $now = now();

        return $now->greaterThan(
            $this->revision_deadline_at->copy()->addMinutes(self::LATE_REVISION_THRESHOLD_MINUTES)
        )
            && $now->lessThanOrEqualTo(
                $this->revision_deadline_at->copy()->addMinutes(self::REVISION_BLOCK_THRESHOLD_MINUTES)
            );
    }
}