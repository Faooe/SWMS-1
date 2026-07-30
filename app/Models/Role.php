<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'code',
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Bootstrap the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Role $role) {
            if (blank($role->uuid)) {
                $role->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Scope active roles.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Permissions owned by this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }
    /**
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    public function users(): HasMany
    {
        return $this->hasMany(
            User::class
        );
    }

    /**
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */
    public function isSuperAdmin(): bool
    {
        return $this->code === 'SUPER_ADMIN';
    }

    public function isAdmin(): bool
    {
        return $this->code === 'ADMIN';
    }

    public function isSupervisor(): bool
    {
        return $this->code === 'SUPERVISOR';
    }

    public function isEmployee(): bool
    {
        return $this->code === 'EMPLOYEE';
    }
}