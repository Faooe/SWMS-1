<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Models\SubscriptionPayment;

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
    | Head Office
    |--------------------------------------------------------------------------
    |
    | Company TIDAK punya kolom latitude/longitude/polygon sendiri --
    | lokasi company (yang diisi/diedit lewat peta di form Platform Admin)
    | sebenarnya disimpan di record Office dengan is_head_office = true
    | (dibuat otomatis saat company baru dibuat, lihat
    | CompanyService::createHeadOffice()). Relasi ini dipakai supaya
    | Platform\CompanyResource (API) bisa ikut mengembalikan
    | latitude/longitude/polygon-nya -- sebelumnya field ini TIDAK ada
    | sama sekali di response API, makanya di aplikasi mobile selalu
    | muncul "Lokasi belum diatur" walau di web datanya sudah ada.
    |
    */

    public function headOffice(): HasOne
    {
        return $this->hasOne(Office::class)->where('is_head_office', true);
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
    | Subscription Payments
    |--------------------------------------------------------------------------
    */

    public function subscriptionPayments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
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
            '!=',
            'Free'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Is Premium
    |--------------------------------------------------------------------------
    */

    public function isPremium(): bool
    {
        return $this->subscription_plan !== 'Free';
    }
}