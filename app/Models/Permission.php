<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'module',
        'action',
        'code',
        'name',
        'description',
        'is_active'
    ];

    protected static function booted(): void
    {
        static::creating(function ($permission) {

            if (empty($permission->uuid)) {

                $permission->uuid = (string) Str::uuid();

            }

        });
    }

/**
 * Get roles assigned to the permission.
 */
public function roles(): BelongsToMany
{
    return $this->belongsToMany(
        Role::class,
        'role_permissions',
        'permission_id',
        'role_id'
    )->withTimestamps();
}
}