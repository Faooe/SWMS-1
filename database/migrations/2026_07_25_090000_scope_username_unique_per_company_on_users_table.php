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
    | Sebelumnya username di tabel users unique secara GLOBAL (lintas
    | company), padahal wajar ada username yang sama di company yang
    | berbeda. Migration ini mengganti unique constraint tunggal itu
    | menjadi composite unique per company: (company_id, username).
    |
    | email dan employee_number (via tabel employees) tetap menjadi
    | identifier login yang unik/tervalidasi secara terpisah, jadi tidak
    | disentuh oleh migration ini.
    |
    */

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropUnique(['username']);

            $table->unique(['company_id', 'username']);

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropUnique(['company_id', 'username']);

            $table->unique('username');

        });
    }
};
