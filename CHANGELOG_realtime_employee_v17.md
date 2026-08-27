# Employee realtime & deadlines v17

- Notifikasi database + FCM `Assignment Baru` saat employee benar-benar mendapat assignment berstatus Assigned.
- Lazy-sync deadline pada endpoint My Assignment agar tidak bergantung logout/cron.
- Assignment aktif yang melewati end_datetime tanpa selesai => `review_status = Not Worked`.
- Needs Revision yang melewati revision_deadline_at tanpa resubmit => `Not Worked`.
- Scheduled command `assignments:expire-revisions` juga menangani kedua deadline di atas.
- Statistik completed hanya menghitung hasil yang sudah Approved; ditambah `not_worked`.
