import imageCompression from "browser-image-compression";

/*
|--------------------------------------------------------------------------
| Kompresi Otomatis Foto Assignment (Web)
|--------------------------------------------------------------------------
|
| Sebelumnya kompresi otomatis cuma jalan di mobile (flutter_image_compress
| -- lihat lib/core/utils/image_compress_helper.dart di project Flutter).
| Di web, browser cuma DIVALIDASI max 300KB oleh backend (CompleteAssignmentRequest),
| tidak ada kompresi -- kalau foto kelebihan, user harus pilih foto lain manual.
|
| Sekarang pakai `browser-image-compression` (gratis, MIT license, jalan
| 100% di browser via Web Worker, tidak ada API/kuota berbayar apapun) --
| supaya perilakunya konsisten dengan mobile: foto otomatis dikompres ke
| bawah 300KB SEBELUM di-submit, user tidak perlu mikirin ukuran foto
| sama sekali.
|
| Batas 300KB HARUS sama persis dengan validasi backend
| (CompleteAssignmentRequest::rules() -> 'max:300', dalam KB) dan mobile
| (kAssignmentPhotoMaxBytes di image_compress_helper.dart).
|
*/

export const ASSIGNMENT_PHOTO_MAX_BYTES = 300 * 1024;
export const GENERAL_IMAGE_MAX_BYTES = 700 * 1024;

/** Generic image compressor for profile/company/employee photos. */
export async function compressImageForUpload(file, maxBytes = GENERAL_IMAGE_MAX_BYTES) {
    if (!file || !file.type?.startsWith('image/') || file.size <= maxBytes) return file;
    const maxSizeMB = maxBytes / (1024 * 1024);
    const attempts = [1600, 1280, 1024, 800, 600];
    let best = file;
    for (const maxWidthOrHeight of attempts) {
        try {
            const compressed = await imageCompression(file, {
                maxSizeMB, maxWidthOrHeight, useWebWorker: true, initialQuality: 0.82, fileType: 'image/jpeg'
            });
            if (compressed.size < best.size) best = compressed;
            if (compressed.size <= maxBytes) return renameCompressedFile(compressed, file.name);
        } catch (_) {}
    }
    return best === file ? file : renameCompressedFile(best, file.name);
}


/**
 * Kompres satu File foto ke bawah ASSIGNMENT_PHOTO_MAX_BYTES.
 *
 * Strategi persis mengikuti pendekatan mobile: turunkan quality secara
 * iteratif, dan kalau masih di atas batas di quality paling rendah,
 * turunkan juga resolusi maksimalnya sambil ulangi. Berhenti begitu
 * ukurannya sudah di bawah batas, atau kalau sudah mentok (supaya tidak
 * infinite loop) -- dalam kasus itu, hasil terkecil yang berhasil
 * didapat tetap dipakai (validasi backend tetap jadi safety-net
 * terakhir kalau foto aslinya benar-benar sangat detail/besar).
 *
 * @param {File} file
 * @returns {Promise<File>}
 */
export async function compressAssignmentPhoto(file) {

    if (!file || !file.type?.startsWith("image/")) {
        return file;
    }

    if (file.size <= ASSIGNMENT_PHOTO_MAX_BYTES) {
        return file;
    }

    const maxSizeMB = ASSIGNMENT_PHOTO_MAX_BYTES / (1024 * 1024);

    // Percobaan bertahap: resolusi maksimum diturunkan tiap gagal
    // mencapai target ukuran, mirip strategi minSide turun di mobile.
    const attempts = [1600, 1280, 1024, 800, 600, 400];

    let best = null;

    for (const maxWidthOrHeight of attempts) {

        try {

            const compressed = await imageCompression(file, {
                maxSizeMB,
                maxWidthOrHeight,
                useWebWorker: true,
                initialQuality: 0.8,
                fileType: "image/jpeg",
                alwaysKeepResolution: false,
            });

            // Simpan hasil terkecil yang pernah didapat, jaga-jaga kalau
            // semua percobaan gagal mencapai target.
            if (!best || compressed.size < best.size) {
                best = compressed;
            }

            if (compressed.size <= ASSIGNMENT_PHOTO_MAX_BYTES) {
                return renameCompressedFile(compressed, file.name);
            }

        } catch (error) {

            console.error("Gagal mengompres foto, mencoba resolusi lebih rendah...", error);

        }

    }

    // Mentok di titik terkecil yang bisa dicapai -- tetap dipakai (lihat
    // catatan di docblock). Kalau kompresi gagal total, kembalikan foto
    // asli, biar validasi backend yang menolak dengan pesan jelas.
    return best ? renameCompressedFile(best, file.name) : file;
}

/**
 * `browser-image-compression` mengembalikan Blob/File dengan nama file
 * generik -- dikembalikan lagi jadi File dengan nama asli (ekstensi
 * disesuaikan ke .jpg karena output selalu di-convert ke JPEG) supaya
 * tetap enak dibaca user & tidak aneh di input file.
 */
function renameCompressedFile(compressedBlob, originalName) {

    const baseName = originalName.replace(/\.[^/.]+$/, "");

    return new File(
        [compressedBlob],
        `${baseName}.jpg`,
        { type: "image/jpeg", lastModified: Date.now() }
    );

}

/**
 * Format ukuran file (bytes) jadi string singkat yang enak dibaca,
 * mis. "184 KB".
 */
export function formatFileSize(bytes) {

    if (bytes < 1024) return `${bytes} B`;

    return `${(bytes / 1024).toFixed(0)} KB`;

}
