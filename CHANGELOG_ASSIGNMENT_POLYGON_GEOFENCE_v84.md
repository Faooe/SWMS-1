# CHANGELOG Assignment Polygon Geofence v84

## Tujuan
Menyelaraskan pembuatan assignment, tampilan map, dan validasi attendance ketika assignment menggunakan polygon.

## Rule akhir
- Polygon valid (>= 3 titik) menjadi geofence utama.
- Radius tidak digunakan dan disimpan `null` ketika polygon aktif.
- Jika polygon tidak ada, assignment wajib memakai radius 50-1000 meter.
- Check In dan Check Out assignment memakai point-in-polygon jika polygon tersedia.
- UI web menampilkan polygon tanpa circle radius ketika polygon aktif.

## Perubahan utama
- Radius assignment dibuat nullable melalui migration baru.
- Request create/update memakai `required_without:polygon` untuk radius.
- AssignmentService menormalisasi payload polygon web/mobile dan menghapus fallback radius saat polygon aktif.
- PolygonService mendukung format `[[lat,lng]]` dan `[{lat,lng}]` untuk kompatibilitas data lama.
- Legacy mobile attendance service sekarang memakai AttendanceLocationService yang sama dengan flow assignment attendance lain.
- Attendance context/resource mengekspos polygon + `geofence_method`.
- Web create/edit, assignment detail, employee assignment detail, attendance card, dan GPS validation diselaraskan.
