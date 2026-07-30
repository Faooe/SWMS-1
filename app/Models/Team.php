<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'company_id',
        'code',
        'name',
        'department_id',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected static function booted(): void
    {
        static::creating(function ($team) {

            if (empty($team->uuid)) {

                $team->uuid = (string) Str::uuid();

            }

        });
    }
    /**
 * Department dari Team
 */
public function department(): BelongsTo
{
    return $this->belongsTo(Department::class);
}
public function employmentHistories(): HasMany
{
    return $this->hasMany(EmploymentHistory::class);
}

/*
|--------------------------------------------------------------------------
| Company
|--------------------------------------------------------------------------
*/

public function company(): BelongsTo
{
    return $this->belongsTo(Company::class);
}

/*
|--------------------------------------------------------------------------
| Scope Current Company
|--------------------------------------------------------------------------
*/

public function scopeForCurrentCompany(Builder $query): Builder
{
    $user = Auth::user();

    if (!$user) {
        return $query;
    }

    if ($user->company_id) {
        $query->where('company_id', $user->company_id);
    }

    return $query;
}
}
