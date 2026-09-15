<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/** Enforces the storage limits for uploaded media by MIME type. */
final class MediaUploadSize implements ValidationRule
{
    public const PHOTO_BYTES = 200 * 1024;

    public const VIDEO_BYTES = 5 * 1024 * 1024;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            return;
        }

        $mime = (string) $value->getMimeType();
        $isVideo = str_starts_with($mime, 'video/');
        $isPdf = $mime === 'application/pdf';
        $limit = $isVideo || $isPdf ? self::VIDEO_BYTES : self::PHOTO_BYTES;

        if (($value->getSize() ?: 0) > $limit) {
            $fail($isVideo
                ? 'Ukuran video maksimal 5 MB.'
                : ($isPdf
                    ? 'Ukuran PDF maksimal 5 MB.'
                    : 'Ukuran foto maksimal 200 KB.'));
        }
    }
}
