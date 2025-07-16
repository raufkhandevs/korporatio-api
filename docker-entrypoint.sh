#!/bin/bash
set -e

# Debug: Print environment variables
echo "=== Environment Debug ==="
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_DATABASE: $DB_DATABASE"
echo "========================"

# Ensure storage and cache are writable
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Ensure SQLite file exists
if [ ! -f /temp/korporatio_api ]; then
    mkdir -p /temp
    touch /temp/korporatio_api
    chmod 777 /temp/korporatio_api
fi

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    su www-data -s /bin/sh -c "php artisan key:generate --force"
fi

# Clear and cache config
su www-data -s /bin/sh -c "php artisan config:clear"
su www-data -s /bin/sh -c "php artisan config:cache"

# Run migrations with retry logic
echo "Running database migrations..."
for i in 1 2 3; do
    if su www-data -s /bin/sh -c "php artisan migrate --force"; then
        echo "Migrations completed successfully"
        break
    else
        echo "Migration attempt $i failed, retrying..."
        sleep 2
    fi
done

# Start PHP-FPM and Nginx via supervisord
echo "Starting services..."
exec "$@" 