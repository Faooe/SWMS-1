# Phase 3 v46 — Daily Attendance Checkout & UI Consistency

## Fixes
- Daily Attendance web UI now uses the same neutral/primary-blue visual language as the rest of SWMS.
- Daily Check In / Check Out actions are shown directly inside the Daily Attendance card.
- Past attendance that was checked in but never checked out is now shown as `INCOMPLETE` / `Belum Check Out`, not `Sedang Bekerja` forever.
- `Selesai x/y` remains the count of required days that have completed Check Out.
- Assignment completion is no longer permanently blocked by an incomplete attendance day in the past. On the final day, the final required attendance session must be checked out before the employee can submit the final assignment result.
- Check Out stays available for an already-open attendance even if the global assignment status changes, unless the assignment is Draft/Cancelled, the employee is Not Worked/Expired, or the checkout deadline has passed.
- Daily Attendance form wording is clarified: enabling Daily Attendance is separate from choosing which days are required (`Work Calendar` vs `Every calendar day`).
- Role 3 assignment header status/priority/daily-attendance badges use a more consistent blue/slate palette.

## Mobile parity
- Daily Attendance card now includes 4 summary metrics: Kehadiran, Selesai, Jam Kerja, Tidak Hadir.
- Daily Check In / Check Out buttons are displayed directly under the calendar.
- Past unclosed attendance displays `Belum Check Out`.
- Generic action area no longer duplicates Daily Attendance Check In / Check Out buttons.
- Company Assignment form wording is clarified for Daily Attendance day rules.
