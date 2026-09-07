# Dashboard Role 2 Audit v69

## Fix
- `attendance_today` now means actual attendance: Present + Late only.
- Yesterday comparison uses the same definition.
- Weekly Attendance trend uses Present + Late only.
- Leave / Permission / Absent remain visible in recent attendance, but are not counted as "Hadir".

## Verified
- Dashboard API keys consumed by mobile remain unchanged.
- Mobile v59 DashboardModel mapping matches backend response keys.
- RecentAttendanceRow requires and receives statusLabel.
- ActiveAssignmentRow does not require statusLabel.
- Company bottom navigation indices remain unchanged.
- Web quick-access route names exist for employees, attendance, assignments, leaves.
- DashboardService.php, Livewire Dashboard.php, and Dashboard API controller pass php -l.

No migration required.
