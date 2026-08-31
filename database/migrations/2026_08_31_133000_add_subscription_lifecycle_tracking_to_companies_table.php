<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->timestamp('subscription_reminder_7_sent_at')->nullable();
            $table->timestamp('subscription_reminder_3_sent_at')->nullable();
            $table->timestamp('subscription_reminder_1_sent_at')->nullable();
            $table->timestamp('subscription_expired_at')->nullable();
        });

        // Free plan tidak memiliki masa kedaluwarsa. Versi lama memberi
        // subscription_end +1 tahun saat company dibuat; bersihkan data itu.
        DB::table('companies')
            ->where('subscription_plan', 'Free')
            ->update(['subscription_end' => null]);
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_reminder_7_sent_at',
                'subscription_reminder_3_sent_at',
                'subscription_reminder_1_sent_at',
                'subscription_expired_at',
            ]);
        });
    }
};
