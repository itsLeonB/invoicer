#!/bin/sh
php artisan config:cache
php artisan route:cache
exec /usr/bin/supervisord -c /etc/supervisord.conf
