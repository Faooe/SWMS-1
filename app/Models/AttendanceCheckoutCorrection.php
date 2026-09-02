<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AttendanceCheckoutCorrection extends Model
{
    protected $fillable = [
        'uuid', 'company_id', 'assignment_id', 'attendance_id', 'employee_id',
        'requested_check_out_time', 'reason', 'status', 'reviewed_by',
        'review_notes', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $model) {
            if (blank($model->uuid)) $model->uuid = (string) Str::uuid();
        });
    }

    public function attendance(): BelongsTo { return $this->belongsTo(Attendance::class); }
    public function assignment(): BelongsTo { return $this->belongsTo(Assignment::class); }
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function isPending(): bool { return $this->status === 'Pending'; }
}
