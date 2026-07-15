<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Shift feature dihapus dari form Add/Edit Employee, jadi kolom
     * shift_id di employment_histories tidak lagi wajib diisi.
     */
    public function up(): void
    {
        Schema::table('employment_histories', function (Blueprint $table) {

            $table->foreignId('shift_id')
                ->nullable()
                ->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employment_histories', function (Blueprint $table) {

            $table->foreignId('shift_id')
                ->nullable(false)
                ->change();

        });
    }
};
