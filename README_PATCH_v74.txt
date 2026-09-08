SWMS Backend v74 - Role 1 Platform Dashboard UI/UX Consistency

Scope:
- Role 1 / Platform Administrator dashboard web only.
- UI/UX consistency with current SWMS visual language.
- No database migration.
- No changes to business logic, API, subscription calculation, or company statistics service.

Changed file:
- resources/views/platform/dashboard/index.blade.php

Highlights:
- Greeting/header aligned with current Role 2 dashboard hierarchy.
- Platform summary consolidated into one bordered card.
- Uses existing statistics only: total company, active company, premium company, total employee, free, inactive, expired.
- New compact Platform Ecosystem status section.
- Quick access for Companies, Premium, Profile.
- Latest companies redesigned with responsive desktop table + mobile cards.
- Consistent badges, borders, spacing, empty states, and terminology.

After deploy:
php artisan optimize:clear
