# Phase 3 QA Checklist — Backend v44 + Mobile v37

1. Run `php artisan migrate` and `php artisan test` in the real backend environment.
2. Create a multi-day assignment with **Daily Attendance** enabled.
3. Confirm Employee calendar shows every required date, including Libur/Belum/Tidak Hadir.
4. Check in and check out; verify late/work/early-leave/overtime values.
5. Regression: ordinary **office attendance check-out** still succeeds and calculates its metrics.
6. Final Daily Attendance day: after assignment `end_datetime` but before **23:00**, check-out is still allowed.
7. In that same grace window, scheduler/lazy sync must **not** change the assignment to `Not Worked`.
8. After the effective final deadline, unfinished active work may become `Not Worked`.
9. Check-out must **not** automatically make the assignment `Completed`.
10. Upload instruction attachments from web/mobile; total stored attachments must never exceed **5 per assignment**, including repeated updates.
11. Image attachments are compressed before upload; PDF files from web are not image-compressed.
12. Attachments appear in Company and Employee detail; tapping them on mobile opens the signed file URL.
13. Completion evidence remains compressed to the assignment-photo target.
14. Profile/employee/company photos remain compressed before upload.
15. Check timeline for Check In, Check Out, completion/resubmission and verify available metrics are shown.
16. Regression: Accept/Reject, Pending Review, Needs Revision, Not Worked, and Phase 2 billing remain normal.
