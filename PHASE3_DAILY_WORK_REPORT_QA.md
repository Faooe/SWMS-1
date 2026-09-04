# Phase 3 QA — Daily Work Report + Final PDF

1. Deploy Backend v55 and run `php artisan migrate`.
2. Install/build Mobile v47.
3. Create a multi-day assignment with Daily Attendance enabled.
4. Employee Check In on a required day.
5. Tap Check Out:
   - Daily Work Report sheet must open first.
   - Description under 5 chars must be rejected.
   - Description valid with no photo must succeed.
   - Up to 3 photos must succeed.
6. Reopen detail:
   - Work description appears under `Laporan Harian Tersimpan`.
   - Photo buttons open signed photo URLs.
7. Repeat across several assignment days.
8. Verify previous days' work descriptions remain attached to their own attendance date.
9. On the final date:
   - Final report button must not be available before final required Check Out.
   - After final required Check Out, `Download Final Report (PDF)` appears.
10. Open PDF and verify:
   - Assignment number/title.
   - Employee name and period.
   - Every calendar date.
   - Check In/Out and work duration.
   - Daily descriptions.
   - Uploaded photos.
   - Summary required/completed/absent/late/work time.
11. Regression:
   - Non-daily assignment Check Out still works without report fields.
   - Office attendance Check Out still works.
   - Missed Check Out correction still works.
   - Assignment Complete / Pending Review / Needs Revision / Approved / Not Worked flows still work.
