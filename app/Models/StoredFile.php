<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Baris tunggal = satu file (logo company / foto employee / foto
 * bukti assignment), isi bytes-nya di kolom 'content'.
 *
 * TIDAK pakai SoftDeletes sengaja -- file yang dihapus (mis. ganti
 * foto employee) memang harus benar-benar hilang, bukan cuma
 * ditandai, supaya baris lama gak numpuk percuma di database.
 *
 * Kolom 'content' isinya base64 (teks), bukan bytea mentah -- lihat
 * App\Services\SecureFileService & migration
 * 2026_08_05_090000_change_files_content_to_text soal kenapa.
 *
 * Model ini HANYA dipakai lewat App\Services\SecureFileService --
 * jangan query tabel 'files' langsung dari Controller/Service lain,
 * supaya satu-satunya "pintu masuk" penyimpanan file tetap terjaga.
 */
class StoredFile extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'path',
        'mime_type',
        'size',
        'content',
    ];
}
