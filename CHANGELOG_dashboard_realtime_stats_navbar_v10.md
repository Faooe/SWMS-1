# V10 - Dashboard Realtime Statistics & Navbar Cleanup

## Dashboard/stat card
- Removed the hardcoded `+12% dibanding bulan lalu` default from `x-dashboard.stat-card`.
- Stat cards only show a comparison when a real comparison value is supplied.
- Positive changes render green, negative changes red, unchanged values neutral.
- Company dashboard comparisons now come from database queries:
  - Employee: current active employee count vs active employees already registered one month ago.
  - Attendance: today's attendance vs yesterday.
  - Late: today's late attendance vs yesterday.
  - Assignment: current active assignment count vs scheduled-active reference one month ago.
- Employee Management `New This Month` compares actual employees created this month vs last month.
- Total/Active/Inactive Employee cards no longer display fake percentages because no trustworthy historical status audit exists for those snapshots.

## Navbar
- Removed the global Search input because it had no form, route, Livewire binding, or JavaScript action.
- Removed the standalone Settings icon because it had no click action/route.
- Kept Notifications because the dropdown has working endpoints/actions.
- Kept Account Settings in the user dropdown because it links to the profile account-settings section.
- Kept all page-level search/filter controls that have actual functionality.
