# Backend v57 — Daily Attendance Status Semantics

## Fixes
- Company Assignment `Active` filter/statistics now require an assignment period that is still active and at least one operational employee pivot.
- Past assignments, all-rejected assignments, Not Worked, Pending Review and Needs Revision are no longer incorrectly counted as Active.
- Daily Attendance expiration now distinguishes zero work from partial work:
  - zero recorded Check In => Not Worked
  - at least one recorded Check In => Pending Review after the period ends
- Existing Daily Attendance records incorrectly stored as Not Worked are self-healed to Pending Review when they have attendance work and are not expired revisions.
- Revision-expired Not Worked remains unchanged.
