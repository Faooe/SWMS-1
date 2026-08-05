<?php

namespace App\Services;

use App\Models\StoredFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

/**
 * Satu-satunya tempat logic penyimpanan file "sensitif" (logo company,
 * foto profil employee, foto bukti pengerjaan assignment) -- dipakai
 * CompanyService, EmployeeService, EmployeeAssignmentService, dan
 * resource mana pun yang butuh nampilin file-file itu. Jangan query
 * tabel 'files' langsung dari luar class ini.
 *
 * PENTING -- kenapa disimpan di DATABASE (Neon Postgres), bukan
 * filesystem: hosting Vercel yang dipakai project ini serverless,
 * satu-satunya folder writable ('/tmp', lihat api/index.php) bersifat
 * EPHEMERAL -- bisa hilang kapan saja begitu container di-recycle,
 * beda instance bisa punya /tmp yang beda-beda juga. Jadi TIDAK ADA
 * folder yang benar-benar persisten di platform ini untuk nyimpen
 * file, apapun nama disknya. Neon Postgres (database yang sudah
 * dipakai project ini) itu service yang beneran persisten & gratis
 * tanpa kartu kredit -- lihat tabel 'files' & App\Models\StoredFile.
 *
 * API publik class ini (store/delete/temporaryUrl) SENGAJA dijaga
 * sama persis seperti versi filesystem sebelumnya -- CompanyService,
 * EmployeeService, EmployeeAssignmentService, dan semua Blade view
 * yang sudah dipindah ke secure_file_url() TIDAK PERLU diubah lagi
 * sama sekali gara-gara perubahan ini. Itulah gunanya sentralisasi.
 */
class SecureFileService
{
    /**
     * Simpan file upload, return "path" (sebenarnya KEY unik, bukan
     * path filesystem beneran) -- format return value SAMA seperti
     * sebelumnya, supaya kolom database yang sudah ada
     * (companies.logo, employees.photo, dst) tetap kompatibel.
     */
    public function store(UploadedFile $file, string $folder): string
    {
        $key = trim($folder, '/') . '/' . Str::uuid() . '.' . $file->extension();

        StoredFile::create([
            'path' => $key,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize() ?: 0,
            // base64, BUKAN bytea mentah -- lihat catatan migration
            // 2026_08_05_090000_change_files_content_to_text soal
            // kenapa PDO_pgsql + bytea selalu gagal ("invalid byte
            // sequence for encoding UTF8") untuk isi file binary.
            'content' => base64_encode(file_get_contents($file->getRealPath())),
        ]);

        return $key;
    }

    /**
     * Hapus file. Aman dipanggil dengan path null/yang sudah tidak
     * ada -- tidak melempar error.
     */
    public function delete(?string $path): void
    {
        if (blank($path)) {

            return;

        }

        StoredFile::where('path', $path)->delete();
    }

    /**
     * Bikin URL sementara (signed, kedaluwarsa otomatis) yang aman
     * ditaruh langsung di <img src="..."> atau field JSON API --
     * TIDAK BERUBAH dari versi filesystem sebelumnya, karena struktur
     * route/URL-nya memang tidak bergantung pada backend penyimpanan.
     *
     * Default 60 menit -- lihat catatan versi sebelumnya soal kenapa
     * bukan cuma beberapa menit (response API dikonsumsi mobile app
     * yang nge-cache data lebih lama dari sekadar "sekali render").
     */
    public function temporaryUrl(?string $path, int $minutes = 60): ?string
    {
        if (blank($path)) {

            return null;

        }

        return URL::temporarySignedRoute(

            'files.show',

            now()->addMinutes($minutes),

            ['path' => $path]

        );
    }

    /**
     * Dipakai App\Http\Controllers\SecureFileController buat benar-benar
     * ngirim isi file-nya setelah signature tervalidasi middleware
     * 'signed'.
     */
    public function exists(string $path): bool
    {
        return StoredFile::where('path', $path)->exists();
    }

    public function response(string $path): Response
    {
        $file = StoredFile::where('path', $path)->firstOrFail();

        return response(base64_decode($file->content), 200, [

            'Content-Type' => $file->mime_type,

            'Content-Length' => $file->size,

            'Cache-Control' => 'private, max-age=3600',

        ]);
    }
}
