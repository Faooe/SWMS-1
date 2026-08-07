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
}