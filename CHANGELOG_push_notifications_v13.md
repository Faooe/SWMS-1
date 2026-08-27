# SWMS v13 - System Push Notifications

- FCM backend now creates Messaging directly from FIREBASE_CREDENTIALS_BASE64.
- Assignment completion submitted -> Company Admin: database + FCM.
- Leave request submitted -> Company Admin: database + FCM.
- Employee marked absent -> Company Admin: database + FCM.
- Assignment approved / needs revision -> Employee: database + FCM.
- Leave approved / rejected -> Employee: database + FCM.
- Notifications are dispatched synchronously so they also work on serverless deployments without a queue worker.

Required backend environment:
- FIREBASE_PROJECT_ID
- FIREBASE_CREDENTIALS_BASE64 (base64-encoded Firebase Service Account JSON)

Use the same Firebase project as the Flutter app.
