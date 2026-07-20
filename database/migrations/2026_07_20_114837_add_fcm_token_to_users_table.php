<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Kolom fcm_token
    |--------------------------------------------------------------------------
    |
    | Menyimpan Firebase Cloud Messaging device token milik user, dikirim
    | dari aplikasi mobile (Flutter) setelah login supaya server bisa
    | mengirim push notification ke HP user tersebut.
    |
    | Nullable karena user yang login lewat web (company admin) tidak
    | selalu pasang aplikasi mobile.
    |
    */

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('fcm_token')->nullable()->after('last_login_ip');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('fcm_token');

        });
    }
};