# Permission Auto Reject v8

- Pending leave/permission requests are automatically rejected after `end_date` has passed.
- Auto-rejection reason: `Ditolak otomatis karena batas tanggal izin telah terlewati tanpa persetujuan admin.`
- Lazy sync runs when employee/company leave lists are loaded, so web/mobile stay correct even if serverless cron is delayed.
- Added Artisan command: `leave-requests:auto-reject-expired`.
- Added protected HTTP cron endpoint: `/cron/auto-reject-leave-requests`.
- Added Vercel daily cron at 16:05 UTC (00:05 Asia/Makassar/WITA).
- Late manual approval/rejection first checks expiration and auto-rejects when necessary.
- API exposes `is_auto_rejected`.
- Web labels system rejections as `Auto Rejected` and shows the reason.
