<?php

namespace App\Http\Controllers;

use App\Services\SecureFileService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Satu-satunya pintu masuk buat "melihat" file yang disimpan
 * SecureFileService (logo company, foto employee, foto bukti
 * assignment). Route-nya (lihat routes/web.php, 'files.show') dijaga
 * middleware 'signed' -- request TIDAK PERNAH sampai method show() di
 * bawah ini kalau signature-nya tidak valid/sudah kedaluwarsa,
 * middleware yang menolaknya duluan (otomatis 403).
 *
 * Sengaja TIDAK ditambah middleware 'auth' di atas 'signed' -- URL
 * signed ini didesain bisa dipasang langsung di <img src="..."> (baik
 * dari halaman web maupun response API buat mobile), yang mana
 * browser/Flutter Image widget tidak bisa menyisipkan header
 * Authorization custom ke request gambar. Keamanannya sepenuhnya dari
 * signature + masa berlaku URL itu sendiri (persis seperti presigned
 * URL S3), bukan dari sesi login.
 */
class SecureFileController extends Controller
{
    public function show(
        Request $request,
        string $path,
        SecureFileService $fileService
    ): StreamedResponse {

        abort_unless(

            $fileService->exists($path),

            404,

            'File tidak ditemukan.'

        );

        return $fileService->response($path);
    }
}
