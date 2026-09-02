# Phase 3 — Company Assignment UI/UX v51

## Fokus
Penyelarasan Role 2 (Company Admin) Assignment di Web dan API agar konsisten dengan workflow Phase 3 serta lebih ringan, rapi, dan mudah direview.

## Perubahan utama
- List Assignment memakai filter Search, Status, Priority, Office, dan Tanggal.
- Statistik Company menghitung assignment unik untuk Active, Pending Review, Needs Revision, Completed, Draft, Rejected, dan Cancelled.
- Needs Revision diprioritaskan atas Pending Review ketika satu assignment memiliki state campuran antar employee.
- List query diringankan: tidak lagi eager-load logs/attachments/team detail yang belum diperlukan.
- N+1 employee count dihilangkan dengan aggregate `withCount`.
- Detail Company dapat melihat Daily Attendance masing-masing employee (calendar + summary), tanpa membuka data employee lain kepada Role 3.
- Team & Review dibuat lebih terstruktur dan collapsible per employee.
- Completion evidence, review action, revision, dan missed-checkout correction dikelompokkan per employee.
- Detail/header/location/timeline/attachments dirapikan secara visual dan konsisten dengan primary blue + neutral surfaces.
- Create/Edit Assignment Web dirapikan: heading benar, section lebih compact, Daily Attendance wording lebih jelas, location + employee picker lebih konsisten.
- Filter employee tidak lagi menampilkan opsi inactive yang memang tidak tersedia pada sumber data aktif.

## Tidak mengubah
- Workflow inti Assignment Phase 3.
- Skema database.
- Kontrak endpoint create/update yang sudah stabil.
