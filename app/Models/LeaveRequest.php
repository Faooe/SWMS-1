<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LeaveRequest extends Model
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

        'type',

        'start_date',

        'end_date',

        'reason',

        'attachment',

        'status',

        'approved_by',

        'approved_at',

        'rejection_reason',

    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'start_date' => 'date',

        'end_date' => 'date',

        'approved_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (LeaveRequest $leaveRequest) {

            if (blank($leaveRequest->uuid)) {

                $leaveRequest->uuid = (string) Str::uuid();

            }

        });
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
    | Approver
    |--------------------------------------------------------------------------
    */

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
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
    | Scope Pending
    |--------------------------------------------------------------------------
    */

    public function scopePending(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'Pending'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Duration (in days, inclusive)
    |--------------------------------------------------------------------------
    */

    public function getDurationAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helper
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    public function canBeReviewed(): bool
    {
        return $this->isPending();
    }
}
