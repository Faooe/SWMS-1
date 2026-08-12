<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Leave Quotas -- Kuota Cuti Tahunan
    |--------------------------------------------------------------------------
    |
    | Satu baris = jatah Cuti (bukan Sakit/Acara -- dua itu tidak pakai
    | kuota) milik SATU employee untuk SATU tahun. Desain year-scoped
    | begini yang bikin "reset per tahun" otomatis terjadi TANPA perlu
    | cron/job reset apapun: begitu kalender masuk tahun baru, employee
    | otomatis dianggap punya kuota penuh lagi (default 12 hari, lihat
    | LeaveQuotaService::DEFAULT_ANNUAL_QUOTA_DAYS) karena baris untuk
    | tahun itu belum ada -- bukan karena ada proses yang "mengembalikan"
    | angka ke 12.
    |
    | Baris di tabel ini HANYA dibuat kalau company admin secara sengaja
    | menyesuaikan jatah seorang employee (mis. dapat tambahan cuti).
    | Kalau tidak pernah disesuaikan, employee manapun tetap otomatis
    | dapat default 12 hari/tahun tanpa perlu ada baris sama sekali --
    | lihat LeaveQuotaService::totalDaysFor().
    |
    | "used_days" SENGAJA TIDAK disimpan sebagai kolom di sini -- selalu
    | dihitung on-the-fly dari SUM durasi LeaveRequest bertipe 'Cuti'
    | berstatus 'Approved' pada tahun terkait (lihat
    | LeaveQuotaService::usedDays()). Ini menghindari counter yang bisa
    | telat sinkron -- sumber kebenarannya cuma satu: baris LeaveRequest
    | yang benar-benar approved.
    |
    */

    public function up(): void
    {
        Schema::create('leave_quotas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('year');

            $table->unsignedTinyInteger('total_days')->default(12);

            $table->timestamps();

            $table->unique(['employee_id', 'year']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_quotas');
    }
};
