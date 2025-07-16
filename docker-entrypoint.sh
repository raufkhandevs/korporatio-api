#!/bin/sh
set -e

# Ensure storage and cache are writable
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Ensure SQLite file exists
if [ ! -f /temp/korporatio_api ]; then
    mkdir -p /temp
    touch /temp/korporatio_api
    chmod 777 /temp/korporatio_api
fi

# Run migrations (ignore errors if DB is locked)
su www-data -s /bin/sh -c "php artisan migrate --force || true"

# Start PHP-FPM and Nginx via supervisord
exec "$@" 