<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentLog extends Model
{
    use HasFactory;

    protected $fillable = [

        'assignment_id',

        'employee_id',

        'user_id',

        'action',

        'description',

        'properties',

    ];

    protected $casts = [

        'properties' => 'array',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

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
    | User
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    public function scopeAction(
        Builder $query,
        string $action
    ): Builder
    {
        return $query->where(
            'action',
            $action
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function isCreated(): bool
    {
        return $this->action === 'ASSIGNMENT_CREATED';
    }

    public function isUpdated(): bool
    {
        return $this->action === 'ASSIGNMENT_UPDATED';
    }

    public function isDeleted(): bool
    {
        return $this->action === 'ASSIGNMENT_DELETED';
    }

    public function isAssigned(): bool
    {
        return $this->action === 'EMPLOYEE_ASSIGNED';
    }

    public function isAccepted(): bool
    {
        return $this->action === 'EMPLOYEE_ACCEPTED';
    }

    public function isRejected(): bool
    {
        return $this->action === 'EMPLOYEE_REJECTED';
    }

    public function isStarted(): bool
    {
        return $this->action === 'EMPLOYEE_STARTED';
    }

    public function isFinished(): bool
    {
        return $this->action === 'EMPLOYEE_FINISHED';
    }
}