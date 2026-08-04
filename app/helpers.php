<?php

use App\Services\SecureFileService;

if (! function_exists('secure_file_url')) {

    /**
     * Shortcut global buat SecureFileService::temporaryUrl() -- dipakai
     * di Blade view supaya gak perlu app(SecureFileService::class)
     * berulang-ulang di banyak file. Untuk kode di dalam Controller/
     * Service, tetap disarankan inject SecureFileService lewat
     * constructor (lebih gampang di-test), helper ini murni buat
     * kenyamanan di view.
     *
     * @param string|null $path Path relatif yang tersimpan di kolom
     *                          database (mis. employees.photo,
     *                          companies.logo). Null aman, return null.
     * @param int $minutes Masa berlaku link, default 60 menit.
     */
    function secure_file_url(?string $path, int $minutes = 60): ?string
    {
        return app(SecureFileService::class)->temporaryUrl($path, $minutes);
    }

}
