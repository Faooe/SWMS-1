<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('radius')->nullable()->default(null)->change();
        });

        // Assignment yang sudah punya polygon tidak lagi menyimpan fallback radius.
        DB::table('assignments')->whereNotNull('polygon')->update(['radius' => null]);
    }

    public function down(): void
    {
        DB::table('assignments')->whereNull('radius')->update(['radius' => 200]);

        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('radius')->default(200)->nullable(false)->change();
        });
    }
};
