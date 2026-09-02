# Phase 3 — Leave / Permission & Attendance History UI/UX (Backend v48)

## Role 3 Web
- Leave / Permission redesigned with neutral/blue SWMS visual language.
- Added yearly request summary: total, pending, approved, rejected.
- Leave quota card now uses the same primary palette and a usage progress bar.
- Added status/type filters and clearer request cards.
- Attendance History redesigned with monthly summary, month/status/type filters, source (Office/Assignment), and work metrics.

## Role 2 Web
- Leave / Permission defaults to Pending requests so future requests are not hidden by a "today" filter.
- Added company summary: pending, active today, approved, total.
- Added search, status, type, and date overlap filters.
- Rejection now supports a reason through a proper review dialog.
- Table hierarchy and actions were redesigned to match SWMS.

## API / Data consistency
- Attendance history API now supports month/status/type filters and returns monthly summary.
- AttendanceResource now exposes work_minutes, early_leave_minutes, and overtime_minutes.
- Leave company approve/reject endpoints are scoped to the logged-in company.
- Employee leave API supports type filtering.
- Leave pagination per_page is bounded to 1..100.
- Overlapping Pending/Approved leave requests are rejected.
- Leave approval is blocked if employee already checked in during the requested period.
- Generated Leave/Permission attendance targets OFFICE records only, avoiding accidental overwrite of Assignment attendance.

No database migration is required.
