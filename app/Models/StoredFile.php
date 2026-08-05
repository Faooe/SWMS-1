<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Baris tunggal = satu file (logo company / foto employee / foto
 * bukti assignment), isi bytes-nya di kolom 'content'.
 *
 * TIDAK pakai SoftDeletes sengaja -- file yang dihapus (mis. ganti
 * foto employee) memang harus benar-benar hilang, bukan cuma
 * ditandai, supaya baris bytea lama gak numpuk percuma di database.
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
