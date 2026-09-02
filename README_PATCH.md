# SWMS Backend/Web — Employee Dashboard Web Refinement Patch

Baseline: Backend v49.

Tujuan patch ini adalah merapikan Dashboard Role 3 (Employee) khusus versi web supaya lebih clean, tidak terasa penuh, dan lebih dekat dengan pola tampilan mobile yang sudah rapi.

## Cara pasang
Copy/merge folder `resources/` ke root project backend.

Tidak ada migration baru.

## Setelah replace
```bash
php artisan optimize:clear
php artisan view:clear
php artisan test
```

## File aktif yang berubah
- `resources/views/employee/dashboard/index.blade.php`
- `resources/views/employee/dashboard/partials/greeting.blade.php`
- `resources/views/employee/dashboard/partials/today-overview.blade.php`
- `resources/views/employee/dashboard/partials/statistics.blade.php`
- `resources/views/employee/dashboard/partials/activities.blade.php`
