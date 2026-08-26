# Assignment Approval & Notification Fix

## Perubahan Backend
- `AssignmentCompletionSubmitted` sekarang langsung membuat database notification (tidak bergantung queue worker).
- FCM channel diaktifkan untuk assignment completion.
- FCM dibuat fail-safe agar error konfigurasi Firebase tidak menggagalkan submit assignment.
- API notification sekarang menyertakan `assignment_id`, `assignment_employee_id`, dan `employee_id` agar mobile bisa deep-link ke detail assignment.
- FCM token dibersihkan saat logout untuk mencegah device yang sama menerima push milik akun sebelumnya.

## Auto Approve
Mekanisme `assignment_auto_approve` yang sudah ada dipertahankan:
- OFF: hasil submit menjadi `Pending Review`, admin menerima notifikasi dan harus Approve/Reject.
- ON: hasil submit langsung `Approved`, tidak mengirim notifikasi review karena tidak ada tindakan admin yang diperlukan.
