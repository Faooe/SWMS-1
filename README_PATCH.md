# Scheduled Assignment Notification Reliability Patch

Replace only:

`app/Services/AssignmentService.php`

Fixes:
- Save database/bell notification before attempting FCM.
- FCM failure can no longer remove the in-app notification.
- Backfill missing AssignmentAssigned notifications for Assigned assignments whose start time is within the last 24 hours.
- Idempotent: existing assignment notifications are not duplicated.

After replacing on backend:

```bash
php artisan optimize:clear
php artisan assignments:activate-scheduled
```

The second command is useful once after deployment because it also runs the reconciliation path and can recover the notification for an assignment that already became Assigned.
