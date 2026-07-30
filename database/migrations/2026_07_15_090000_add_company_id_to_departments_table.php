<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_id')

                ->nullable()

                ->after('id')

                ->constrained('companies')

                ->cascadeOnDelete();

        });

        /*
        |--------------------------------------------------------------------------
        | Code sekarang unik per company, bukan unik secara global
        |--------------------------------------------------------------------------
        */

        Schema::table('departments', function (Blueprint $table) {

            $table->dropUnique(['code']);

            $table->unique(['company_id', 'code']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {

            $table->dropUnique(['company_id', 'code']);

            $table->dropForeign(['company_id']);

            $table->dropColumn('company_id');

            $table->unique('code');

        });
    }
};
