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
    | Sebelumnya employee_number dan email di tabel employees unique secara
    | GLOBAL (lintas company), padahal seharusnya cuma unik di dalam satu
    | company yang sama -- dua company yang berbeda bisa saja punya karyawan
    | dengan email pribadi yang kebetulan sama. Migration ini mengganti
    | unique constraint tunggal itu menjadi composite unique per company.
    |
    */

    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {

            $table->dropUnique(['employee_number']);

            $table->dropUnique(['email']);

            $table->unique(['company_id', 'employee_number']);

            $table->unique(['company_id', 'email']);

        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {

            $table->dropUnique(['company_id', 'employee_number']);

            $table->dropUnique(['company_id', 'email']);

            $table->unique('employee_number');

            $table->unique('email');

        });
    }
};
