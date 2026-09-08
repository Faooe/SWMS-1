SWMS Backend Patch v73 - Subscription & Billing Title Escape Fix

Base: Backend v72
Changed file:
- resources/views/partials/navbar.blade.php

Fix:
- Decode HTML entities once when resolving the global navbar page title.
- Prevents "Subscription &amp; Billing" from being displayed literally.
- Keeps final Blade output escaped safely.

No database migration.
No subscription/Midtrans/business-logic changes.

After deploying:
php artisan optimize:clear
