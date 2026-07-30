<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Order
            |--------------------------------------------------------------------------
            */

            $table->string('order_id')->unique();

            $table->string('plan', 50);

            $table->string('duration', 20);

            $table->unsignedBigInteger('gross_amount');

            /*
            |--------------------------------------------------------------------------
            | Midtrans
            |--------------------------------------------------------------------------
            */

            $table->string('snap_token')->nullable();

            $table->string('midtrans_transaction_id')->nullable();

            $table->string('payment_type')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            |
            | pending    -> Snap token dibuat, belum dibayar.
            | settlement -> Pembayaran sukses (sudah upgrade plan).
            | expired    -> Snap token kadaluarsa, tidak dibayar.
            | failed     -> Ditolak / gagal / dibatalkan.
            |
            */

            $table->string('status', 20)->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->json('callback_payload')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'status']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
