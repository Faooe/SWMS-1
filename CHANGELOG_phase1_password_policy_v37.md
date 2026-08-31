# Phase 1 Password Policy Consistency — Backend v37

## Perubahan

- Password otomatis Company Admin/Role 2 sekarang 12 karakter (sebelumnya 6).
- Password otomatis Employee import sekarang memakai generator yang sama.
- Generator menjamin minimal satu huruf besar, satu huruf kecil, dan satu angka.
- Karakter yang mudah tertukar seperti `0/O` dan `1/l/I` dihindari.
- Password manual saat create/update Employee sekarang mengikuti policy yang sama: minimal 8 karakter, mixed case, dan angka.
- Password manual pada CSV import juga mengikuti policy yang sama.
- Password lama yang sudah tersimpan, termasuk password 6 karakter, **tetap bisa dipakai untuk login**. Policy baru hanya berlaku saat membuat/generate/mengganti password baru.
- Ditambahkan unit test untuk memastikan generator selalu memenuhi policy.

## Migration

Tidak ada migration database baru.
