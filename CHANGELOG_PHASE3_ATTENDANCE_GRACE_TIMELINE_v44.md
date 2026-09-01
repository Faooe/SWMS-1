# SWMS Backend v44 — Phase 3 Attendance Grace & Timeline Hardening

Base: **Backend v43 Phase 3 Attendance + Assignment**.

## Fixes

- Fixed office check-out regression in `AttendanceService`: removed an invalid metrics calculation that referenced an undefined assignment.
- Fixed assignment check-out metrics so `work_minutes`, `early_leave_minutes`, and `overtime_minutes` are calculated before attendance is updated.
- Daily Attendance final-day grace is now consistent until **23:00 local application time**:
  - assignment check-out remains available after the scheduled `end_datetime` on the final day;
  - `my_actions.can_check_out` and `my_actions.can_complete` use the same effective deadline;
  - lazy `Not Worked` synchronization waits until the grace deadline;
  - the `assignments:expire-revisions` command also respects the same grace deadline.
- Check-in logs now persist `attendance_date`, `attendance_status`, and `late_minutes` in log properties.
- Check-out logs continue to expose work/early-leave/overtime metrics for the mobile timeline.
- Assignment update responses now reload `attachments` so newly uploaded instruction files are returned immediately.
- Instruction attachments now enforce **5 files total per assignment**, including files already uploaded on previous updates.

## Expected behavior

- Check-out does **not** mark the assignment Completed.
- On the final Daily Attendance day, an employee can still check out/submit completion after scheduled end time but before 23:00.
- After the effective final deadline, unfinished active work can transition to `Not Worked`.
