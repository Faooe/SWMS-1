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

        'subscription_reminder_7_sent_at',

        'subscription_reminder_3_sent_at',

        'subscription_reminder_1_sent_at',

        'subscription_expired_at',

        'max_employee',

        'is_active',

        'assignment_revision_minutes',

        'assignment_auto_approve',

    ];

    protected $casts = [

        'subscription_start' => 'date',

        'subscription_end' => 'date',

        'subscription_reminder_7_sent_at' => 'datetime',

        'subscription_reminder_3_sent_at' => 'datetime',

        'subscription_reminder_1_sent_at' => 'datetime',

        'subscription_expired_at' => 'datetime',

        'assignment_auto_approve' => 'boolean',

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


    public function workSchedule(): HasOne
    {
        return $this->hasOne(CompanyWorkSchedule::class);
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(CompanyHoliday::class);
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

        return $query
            ->where('subscription_plan', '!=', 'Free')
            ->where(function (Builder $query) {
                $query->whereNull('subscription_end')
                    ->orWhereDate('subscription_end', '>=', today());
            });

    }

    /*
    |--------------------------------------------------------------------------
    | Is Premium
    |--------------------------------------------------------------------------
    */

    public function isPremium(): bool
    {
        if ($this->subscription_plan === 'Free') {
            return false;
        }

        // Fail-safe: walaupun cron downgrade terlambat/gagal, benefit premium
        // langsung dianggap tidak aktif setelah tanggal berakhir lewat.
        return $this->subscription_end === null
            || $this->subscription_end->endOfDay()->greaterThanOrEqualTo(now());
    }
}