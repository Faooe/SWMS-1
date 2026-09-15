import imageCompression from "browser-image-compression";

/*
|--------------------------------------------------------------------------
| Kompresi Otomatis Foto Assignment (Web)
|--------------------------------------------------------------------------
|
| Sebelumnya kompresi otomatis cuma jalan di mobile (flutter_image_compress
| -- lihat lib/core/utils/image_compress_helper.dart di project Flutter).
| Di web, foto sekarang otomatis dikompres ke maksimal 200KB sebelum dikirim.
|
| Sekarang pakai `browser-image-compression` (gratis, MIT license, jalan
| 100% di browser via Web Worker, tidak ada API/kuota berbayar apapun) --
| supaya perilakunya konsisten dengan mobile: foto otomatis dikompres ke
| bawah 200KB SEBELUM di-submit, user tidak perlu mikirin ukuran foto
| sama sekali.
|
| Batas foto upload adalah 200KB agar penyimpanan tetap ringan dan konsisten
| dengan mobile (kPhotoMaxBytes di image_compress_helper.dart).
|
*/

export const PHOTO_MAX_BYTES = 200 * 1024;
export const VIDEO_MAX_BYTES = 5 * 1024 * 1024;
export const VIDEO_MAX_DURATION_SECONDS = 60;
// Alias lama untuk pemanggil inline/Blade yang sudah ada.
export const ASSIGNMENT_PHOTO_MAX_BYTES = PHOTO_MAX_BYTES;
export const GENERAL_IMAGE_MAX_BYTES = PHOTO_MAX_BYTES;

function announceCompression(file, maxBytes, kind = "image") {
    if (typeof window === "undefined") return;
    window.dispatchEvent(new CustomEvent("swms:file-compression-start", {
        detail: { name: file.name, originalSize: file.size, maxBytes, kind },
    }));
}

function isVideo(file) {
    return Boolean(file?.type?.startsWith("video/"));
}

async function readVideoDuration(file) {
    const url = URL.createObjectURL(file);
    const video = document.createElement("video");
    video.preload = "metadata";
    video.src = url;
    try {
        await new Promise((resolve, reject) => {
            video.onloadedmetadata = resolve;
            video.onerror = reject;
        });
        return Number.isFinite(video.duration) ? video.duration : null;
    } finally {
        URL.revokeObjectURL(url);
    }
}

/** Compress a browser video to WebM when it exceeds the 5MB upload limit. */
export async function compressVideoForUpload(file, maxBytes = VIDEO_MAX_BYTES) {
    if (!isVideo(file)) return file;

    const duration = await readVideoDuration(file);
    if (duration != null && duration > VIDEO_MAX_DURATION_SECONDS + 0.5) {
        window.dispatchEvent(new CustomEvent("swms:file-duration-invalid", {
            detail: { kind: "video", maxSeconds: VIDEO_MAX_DURATION_SECONDS },
        }));
        throw new Error("Durasi video maksimal 1 menit.");
    }

    if (file.size <= maxBytes) return file;
    announceCompression(file, maxBytes, "video");

    if (typeof MediaRecorder === "undefined" || !HTMLVideoElement.prototype.captureStream) {
        window.dispatchEvent(new CustomEvent("swms:file-compression-unavailable", {
            detail: { kind: "video", message: "Browser ini belum mendukung kompresi video otomatis." },
        }));
        return file;
    }

    const url = URL.createObjectURL(file);
    const video = document.createElement("video");
    video.muted = true;
    video.playsInline = true;
    video.preload = "metadata";
    video.src = url;
    try {
        await new Promise((resolve, reject) => {
            video.onloadedmetadata = resolve;
            video.onerror = reject;
        });
        const videoDuration = Number.isFinite(video.duration) && video.duration > 0 ? video.duration : 60;
        const targetBitrate = Math.max(180000, Math.floor((maxBytes * 8 * 0.82) / videoDuration));
        const mimeType = ["video/webm;codecs=vp9,opus", "video/webm;codecs=vp8,opus", "video/webm"]
            .find((type) => MediaRecorder.isTypeSupported(type));
        if (!mimeType) return file;

        const stream = video.captureStream();
        const compressed = await new Promise((resolve) => {
            const chunks = [];
            const recorder = new MediaRecorder(stream, {
                mimeType,
                videoBitsPerSecond: Math.min(targetBitrate, 2500000),
            });
            recorder.ondataavailable = (event) => event.data.size && chunks.push(event.data);
            recorder.onerror = () => resolve(null);
            recorder.onstop = () => resolve(new Blob(chunks, { type: mimeType }));
            video.onended = () => recorder.state !== "inactive" && recorder.stop();
            recorder.start(250);
            video.play().catch(() => recorder.stop());
        });
        if (!compressed) return file;
        return compressed.size <= maxBytes
            ? new File([compressed], file.name.replace(/\.[^/.]+$/, ".webm"), { type: mimeType, lastModified: Date.now() })
            : file;
    } finally {
        URL.revokeObjectURL(url);
    }
}

export async function compressMediaForUpload(file) {
    if (isVideo(file)) return compressVideoForUpload(file);
    return compressImageForUpload(file);
}

/** Generic image compressor for profile/company/employee photos. */
export async function compressImageForUpload(file, maxBytes = GENERAL_IMAGE_MAX_BYTES) {
    if (!file || !file.type?.startsWith('image/') || file.size <= maxBytes) return file;
    announceCompression(file, maxBytes);
    const maxSizeMB = maxBytes / (1024 * 1024);
    const attempts = [1600, 1280, 1024, 800, 600, 400, 300, 200];
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

    announceCompression(file, ASSIGNMENT_PHOTO_MAX_BYTES);

    const maxSizeMB = ASSIGNMENT_PHOTO_MAX_BYTES / (1024 * 1024);

    // Percobaan bertahap: resolusi maksimum diturunkan tiap gagal
    // mencapai target ukuran, mirip strategi minSide turun di mobile.
    const attempts = [1600, 1280, 1024, 800, 600, 400, 300, 200];

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
