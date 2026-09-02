# SWMS Backend/Web — Employee Dashboard UI/UX Patch

Baseline: Backend v48.

Copy/merge folder `resources/` ke root project backend.

Tidak ada migration baru.

Sesudah replace:

```bash
php artisan optimize:clear
php artisan view:clear
php artisan test
```

File aktif yang berubah:
- `resources/views/employee/dashboard/index.blade.php`
- `resources/views/employee/dashboard/partials/greeting.blade.php`
- `resources/views/employee/dashboard/partials/today-overview.blade.php` (baru)
- `resources/views/employee/dashboard/partials/statistics.blade.php`
- `resources/views/employee/dashboard/partials/activities.blade.php`
- `resources/views/employee/dashboard/partials/quick-actions.blade.php`
