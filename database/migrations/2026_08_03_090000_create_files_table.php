<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel penyimpanan file (logo company, foto employee, foto bukti
 * pengerjaan assignment) -- isi file (bytes mentah) disimpan LANGSUNG
 * di kolom 'content' (bytea), BUKAN di filesystem.
 *
 * Kenapa: hosting Vercel yang dipakai project ini itu serverless --
 * satu-satunya folder writable ('/tmp', lihat api/index.php) bersifat
 * ephemeral, isinya bisa hilang kapan saja begitu container di-recycle.
 * Jadi TIDAK ADA folder yang benar-benar persisten untuk nyimpen file
 * di platform ini -- baik disk 'public' maupun 'local' sama-sama
 * numpang di '/tmp'. Neon Postgres (database yang sudah dipakai
 * project ini) itu service yang beneran persisten & gratis tanpa
 * kartu kredit, jadi paling masuk akal buat nyimpen file di sana.
 *
 * 'path' berperan sebagai KEY unik (bukan path filesystem beneran) --
 * kolom companies.logo / employees.photo / assignment_employees.
 * completion_photo tetap menyimpan string ini persis seperti
 * sebelumnya, cuma sekarang string itu jadi kunci pencarian ke tabel
 * ini (lewat SecureFileService), bukan lagi path folder di disk.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {

            $table->id();

            $table->string('path')->unique();

            $table->string('mime_type', 100);

            $table->unsignedBigInteger('size');

            $table->binary('content');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
