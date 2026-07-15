<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Office extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'uuid',

        'company_id',

        'code',

        'name',

        'address',

        'city',

        'province',

        'postal_code',

        'timezone',

        'latitude',

        'longitude',

        'radius',

        'polygon',

        'is_active',

        'is_head_office',

    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'latitude' => 'float',

        'longitude' => 'float',

        'radius' => 'integer',

        'polygon' => 'array',

        'is_active' => 'boolean',

        'is_head_office' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (Office $office) {

            if (blank($office->uuid)) {

                $office->uuid = (string) Str::uuid();

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
    | Employment Histories
    |--------------------------------------------------------------------------
    */

    public function employmentHistories(): HasMany
    {
        return $this->hasMany(
            EmploymentHistory::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employees
    |--------------------------------------------------------------------------
    */

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(

            Employee::class,

            EmploymentHistory::class,

            'office_id',

            'id',

            'id',

            'employee_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Attendances
    |--------------------------------------------------------------------------
    */

    public function attendances(): HasMany
    {
        return $this->hasMany(
            Attendance::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Assignments
    |--------------------------------------------------------------------------
    */

    public function assignments(): HasMany
    {
        return $this->hasMany(
            Assignment::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Full Address
    |--------------------------------------------------------------------------
    */

    public function getFullAddressAttribute(): string
    {
        return collect([

            $this->address,

            $this->city,

            $this->province,

            $this->postal_code,

        ])

        ->filter()

        ->implode(', ');
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
            'is_active',
            true
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Head Office
    |--------------------------------------------------------------------------
    */

    public function scopeHeadOffice(
        Builder $query
    ): Builder {

        return $query->where(
            'is_head_office',
            true
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
}