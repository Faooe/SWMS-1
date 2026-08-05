<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ganti kolom files.content dari bytea -> text, isinya sekarang
 * base64 (lihat App\Services\SecureFileService).
 *
 * Kenapa: PDO_pgsql mengirim SEMUA parameter sebagai teks, termasuk
 * ke kolom bytea -- Postgres lalu memvalidasi teks itu sebagai UTF-8
 * sebelum insert, dan byte mentah file (gambar dsb) hampir pasti
 * bukan UTF-8 valid (mis. 0xFF di header JPEG) -> selalu gagal
 * dengan "invalid byte sequence for encoding UTF8". Base64 selalu
 * berupa teks ASCII valid, jadi masalah ini hilang total.
 *
 * Aman drop-lalu-add (bukan pakai ->change(), yang butuh paket
 * doctrine/dbal yang belum tentu ter-install) -- tabel 'files' baru
 * dibuat dan setiap percobaan insert sejauh ini SELALU gagal karena
 * bug di atas, jadi dipastikan belum ada data valid yang perlu
 * dipertahankan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {

            $table->dropColumn('content');

        });

        Schema::table('files', function (Blueprint $table) {

            $table->text('content')->after('size');

        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {

            $table->dropColumn('content');

        });

        Schema::table('files', function (Blueprint $table) {

            $table->binary('content')->after('size');

        });
    }
};
