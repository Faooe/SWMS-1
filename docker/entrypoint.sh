#!/bin/bash
set -e

cd /var/www/html

# Cache config, routes, views for performance (safe to fail on first boot)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Ensure storage link exists
php artisan storage:link || true

# Start supervisor (runs nginx + php-fpm)
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf