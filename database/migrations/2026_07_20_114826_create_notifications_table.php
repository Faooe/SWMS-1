<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Tabel Notifications (Bawaan Laravel)
    |--------------------------------------------------------------------------
    |
    | Dipakai oleh Notifiable trait di User model untuk channel 'database'.
    | Ini yang jadi sumber data bell/badge notifikasi di dashboard web.
    | Struktur kolomnya standar Laravel -- jangan diubah namanya, karena
    | dipakai otomatis oleh Illuminate\Notifications\DatabaseNotification.
    |
    */

    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->string('type');

            $table->morphs('notifiable');

            $table->text('data');

            $table->timestamp('read_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};