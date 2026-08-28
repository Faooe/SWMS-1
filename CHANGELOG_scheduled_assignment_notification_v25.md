# v25 — Scheduled Assignment Notification Reliability

- Centralized AssignmentAssigned delivery for direct and scheduled activation.
- Idempotent database notifications to prevent duplicate bell events.
- Database fallback if the notification pipeline throws.
- Scheduled activation recipient logging and missing FCM-token diagnostics.
- Lazy activation fallback when Company opens Assignment list.
- Notification API exposes assignment_uuid.
