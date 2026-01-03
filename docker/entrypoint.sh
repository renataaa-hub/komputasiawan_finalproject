#!/bin/sh
set -e

php artisan config:clear || true
php artisan route:clear  || true
php artisan view:clear   || true

rm -f bootstrap/cache/config.php bootstrap/cache/routes-v7.php bootstrap/cache/services.php 2>/dev/null || true

exec apache2-foreground
