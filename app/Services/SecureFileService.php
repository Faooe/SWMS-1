<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Satu-satunya tempat logic penyimpanan file "sensitif" (logo company,
 * foto profil employee, foto bukti pengerjaan assignment) -- dipakai
 * CompanyService, EmployeeService, EmployeeAssignmentService, dan resource
 * mana pun yang butuh nampilin file-file itu. Jangan panggil
 * Storage::disk('local')/UploadedFile::store() langsung dari luar
 * class ini untuk ketiga jenis file di atas -- supaya SATU tempat ini
 * yang nentuin disk mana yang dipakai & bagaimana cara aksesnya,
 * bukan tersebar di banyak Service dengan potensi salah/lupa.
 *
 * Disk yang dipakai: 'local' (bukan 'public'). Sejak Laravel 11, disk
 * 'local' bawaan mengarah ke storage/app/private -- folder ini TIDAK
 * di-symlink ke public/ dan TIDAK bisa diakses langsung lewat URL
 * manapun. Satu-satunya jalan masuk adalah lewat temporaryUrl() di
 * bawah, yang menghasilkan link bertanda tangan (signed) dan
 * kedaluwarsa otomatis -- lihat routes/web.php ('files.show') &
 * App\Http\Controllers\SecureFileController.
 */
class SecureFileService
{
    private const DISK = 'local';

    /**
     * Simpan file upload ke disk private, return path relatif (format
     * path-nya SAMA seperti sebelumnya waktu masih pakai disk 'public'
     * -- jadi kolom database yang sudah ada, mis. employees.photo,
     * companies.logo, tetap kompatibel, cuma disk fisiknya yang beda).
     */
    public function store(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, self::DISK);
    }

    /**
     * Hapus file dari disk private. Aman dipanggil dengan path null/
     * file yang sudah tidak ada -- tidak melempar error.
     */
    public function delete(?string $path): void
    {
        if (blank($path)) {

            return;

        }

        if (Storage::disk(self::DISK)->exists($path)) {

            Storage::disk(self::DISK)->delete($path);

        }
    }

    /**
     * Bikin URL sementara (signed, kedaluwarsa otomatis) yang aman
     * ditaruh langsung di <img src="..."> atau field JSON API -- tidak
     * butuh header Authorization tambahan (makanya bisa dipasang
     * langsung di tag <img>), keamanannya dari signature URL itu
     * sendiri, sama seperti presigned URL S3.
     *
     * Default 60 menit -- CATATAN: sengaja bukan cuma beberapa menit.
     * Response API dikonsumsi mobile app yang nge-cache data di layar
     * (mis. daftar employee) lebih lama dari sekadar "sekali render" --
     * kalau expiry-nya terlalu pendek, foto bakal "putus" (403) padahal
     * user belum ngapa-ngapain, cuma diem di layar yang sama beberapa
     * menit. 60 menit tetap membatasi umur link secara berarti (bukan
     * URL permanen/publik), tapi gak ganggu pemakaian normal.
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
        return Storage::disk(self::DISK)->exists($path);
    }

    public function response(string $path)
    {
        return Storage::disk(self::DISK)->response($path);
    }
}
