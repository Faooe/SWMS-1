# Phase 3 — Daily Work Report & Final PDF (Backend v55)

## Added
- Daily Attendance check-out stores a required daily work description.
- Up to 3 optional daily work photos are stored through SecureFileService.
- Daily attendance calendar payload exposes work description and signed photo URLs.
- Final Daily Assignment Report PDF endpoint:
  - API: `GET /api/v1/my-assignments/{uuid}/daily-report/pdf`
  - Web: `GET /employee/assignments/{uuid}/daily-report/pdf`
- Final PDF includes assignment/employee metadata, daily attendance, work descriptions, photos, and summary.
- Final report becomes available starting on the assignment's final date after the final required attendance is checked out.
- Employee web Daily Attendance check-out form now supports the same report fields.

## Database
Run:
```bash
php artisan migrate
```

New attendance columns:
- `daily_report_notes`
- `daily_report_photos`

## Compatibility
- Non-Daily Attendance assignment check-out remains unchanged.
- Office attendance flow is unchanged.
- Existing Daily Attendance metrics, correction flow, completion/review flow, and grace deadline logic are preserved.
