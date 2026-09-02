# Backend v45 — Phase 3 Web UI/UX Consistency

## Role 3 Web
- Assignment Detail diselaraskan dengan state API/Mobile Phase 3.
- Daily Attendance calendar + summary + metrics ditambahkan.
- Action Check In/Out/Completion/Revision mengikuti `my_actions` backend.
- Daily Attendance hari berikutnya mendukung Check In saat pivot tetap `In Progress`.
- Daily Check Out tidak lagi dipaksa menunggu completion evidence.
- Status header/card memakai employee/review status yang benar.
- Timeline difilter ke event umum + employee login dan mengenali event Phase 3.
- My Assignment list/filter/statistics diperbarui untuk Pending Review/Needs Revision/Not Worked.
- Attachment, description, location, team, completion form diperbarui UI/UX-nya.
- Auto-refresh ringan pada pergantian hari/jam mulai tanpa polling API.

## Role 2 Web consistency
- Detail menampilkan Attendance Mode / rule.
- Header memakai company display status.
- Timeline mengenali event + metrics Phase 3.
- Shared attachment card diperbarui.

## Backend
- Web detail menggunakan `AssignmentResource` sebagai source-of-truth action state.
- Filter Accepted / In Progress diperbaiki.
- Check Out tetap tersedia ketika review sudah Approved selama attendance belum ditutup.

Tidak ada migration baru.
