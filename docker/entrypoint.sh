#!/bin/sh
if [ -z "$APP_KEY" ]; then
    [ -f .env ] || cp .env.example .env
    php artisan key:generate --force
fi
php artisan config:cache
php artisan route:cache
exec /usr/bin/supervisord -c /etc/supervisord.conf
