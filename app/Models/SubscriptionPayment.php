<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SubscriptionPayment extends Model
{
    protected $fillable = [

        'uuid',

        'company_id',

        'user_id',

        'order_id',

        'plan',

        'duration',

        'gross_amount',

        'snap_token',

        'midtrans_transaction_id',

        'payment_type',

        'status',

        'paid_at',

        'callback_payload',

    ];

    protected $casts = [

        'gross_amount' => 'integer',

        'paid_at' => 'datetime',

        'callback_payload' => 'array',

    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (SubscriptionPayment $payment) {

            if (blank($payment->uuid)) {

                $payment->uuid = (string) Str::uuid();

            }

        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'settlement';
    }
}
