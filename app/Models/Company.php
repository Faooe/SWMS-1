<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'code',

        'name',

        'email',

        'phone',

        'website',

        'logo',

        'address',

        'city',

        'province',

        'postal_code',

        'timezone',

        'subscription_plan',

        'subscription_start',

        'subscription_end',

        'max_employee',

        'is_active',

    ];

    protected $casts = [

        'subscription_start' => 'date',

        'subscription_end' => 'date',

        'max_employee' => 'integer',

        'is_active' => 'boolean',

    ];

    protected static function booted(): void
    {
        static::creating(function (Company $company) {

            if (blank($company->uuid)) {

                $company->uuid = (string) Str::uuid();

            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Employees
    |--------------------------------------------------------------------------
    */

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Offices
    |--------------------------------------------------------------------------
    */

    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attendances
    |--------------------------------------------------------------------------
    */

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Assignments
    |--------------------------------------------------------------------------
    */

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
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
    public function scopePremium(
    Builder $query
    ): Builder {

        return $query->where(
            'subscription_plan',
            'Premium'
        );

    }
}