<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
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

        'role_id',

        'username',

        'email',

        'password',

        'is_active',

        'last_login_at',

        'last_login_ip',

        'password_changed_at',

        'email_verified_at',

    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [

        'password',

        'remember_token',

    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'email_verified_at' => 'datetime',

        'password_changed_at' => 'datetime',

        'last_login_at' => 'datetime',

        'is_active' => 'boolean',

        'password' => 'hashed',

    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (User $user) {

            if (blank($user->uuid)) {

                $user->uuid = (string) Str::uuid();

            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scope
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
    | Role
    |--------------------------------------------------------------------------
    */

    public function role(): BelongsTo
    {
        return $this->belongsTo(
            Role::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Created Assignments
    |--------------------------------------------------------------------------
    */

    public function createdAssignments(): HasMany
    {
        return $this->hasMany(
            Assignment::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Logs
    |--------------------------------------------------------------------------
    */

    public function assignmentLogs(): HasMany
    {
        return $this->hasMany(
            AssignmentLog::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helper
    |--------------------------------------------------------------------------
    */

    public function hasRole(
        string ...$roles
    ): bool {

        if (!$this->relationLoaded('role')) {

            $this->load('role');

        }

        return in_array(

            $this->role?->code,

            $roles,

            true

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Platform Admin
    |--------------------------------------------------------------------------
    */

    public function isPlatformAdmin(): bool
    {
        return $this->hasRole(
            'PLATFORM_ADMIN'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Super Admin
    |--------------------------------------------------------------------------
    */

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(
            'SUPER_ADMIN'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    public function isEmployee(): bool
    {
        return $this->hasRole(
            'EMPLOYEE'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Company Admin
    |--------------------------------------------------------------------------
    */

    public function isCompanyAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    /*
    |--------------------------------------------------------------------------
    | Platform Access
    |--------------------------------------------------------------------------
    */

    public function canAccessPlatform(): bool
    {
        return $this->isPlatformAdmin();
    }

    /*
    |--------------------------------------------------------------------------
    | Company Access
    |--------------------------------------------------------------------------
    */

    public function canAccessCompany(): bool
    {
        return $this->isSuperAdmin();
    }

    /*
    |--------------------------------------------------------------------------
    | Active User
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->is_active;
    }

    /*
    |--------------------------------------------------------------------------
    | Company Ownership
    |--------------------------------------------------------------------------
    */

    public function belongsToCompany(
        int $companyId
    ): bool {

        return $this->company_id === $companyId;

    }
}