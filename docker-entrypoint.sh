#!/bin/sh
set -e

echo "Starting Laravel application..."

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

# Run migrations
su www-data -s /bin/sh -c "php artisan migrate --force" || echo "Migration failed, continuing..."

# Start PHP server
echo "Starting PHP server on port 8080..."
exec php artisan serve --host=0.0.0.0 --port=8080 