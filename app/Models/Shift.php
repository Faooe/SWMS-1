<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Shift extends Model
{
    use SoftDeletes;

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'uuid',
        'code',
        'name',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'late_tolerance',
        'work_days',
        'is_night_shift',
        'is_active',
    ];

    /**
     * Attribute Casting
     */
    protected $casts = [
        'work_days' => 'array',
        'is_night_shift' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Generate UUID otomatis.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Shift $shift) {

            if (blank($shift->uuid)) {
                $shift->uuid = (string) Str::uuid();
            }

        });
    }

    /**
     * Scope active shift.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Employees.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
        /**
     * Attendances
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}