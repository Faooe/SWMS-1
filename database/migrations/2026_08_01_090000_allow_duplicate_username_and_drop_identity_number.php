<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Kenapa Migration Ini Ada
    |--------------------------------------------------------------------------
    |
    | 1) Username BUKAN lagi kredensial login (login hanya pakai Email
    |    atau NIP+Kode Company -- lihat AuthService::loginWeb() /
    |    loginEmployeeWeb() / loginApi() / loginByEmployeeNumber()).
    |    Username sekarang murni nama panggilan/tampilan, jadi boleh
    |    sama persis antar user -- termasuk dalam 1 company yang sama.
    |    Constraint unique(company_id, username) dari migration
    |    2026_07_25_090000 dihapus di sini.
    |
    | 2) Kolom identity_number di tabel employees tidak pernah dipakai
    |    di logic apa pun (bukan buat login, attendance, report, dsb) --
    |    cuma nomor 9 digit acak yang disimpan tanpa fungsi. Dihapus.
    |
    */

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropUnique(['company_id', 'username']);

        });

        Schema::table('employees', function (Blueprint $table) {

            $table->dropColumn('identity_number');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->unique(['company_id', 'username']);

        });

        Schema::table('employees', function (Blueprint $table) {

            $table->string('identity_number', 30)->nullable();

        });
    }
};
