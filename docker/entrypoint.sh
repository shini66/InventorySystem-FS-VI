#!/bin/sh
set -e

cd /var/www/html

php artisan config:clear
php artisan package:discover --ansi

if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
