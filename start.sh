#!/bin/bash
set -e

echo "=== Starting Laravel Application ==="

# Debug environment
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_DATABASE: $DB_DATABASE"

# Ensure SQLite file exists
if [ ! -f /temp/korporatio_api ]; then
    echo "Creating SQLite database file..."
    mkdir -p /temp
    touch /temp/korporatio_api
    chmod 777 /temp/korporatio_api
fi

# Generate key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache config
echo "Clearing and caching config..."
php artisan config:clear
php artisan config:cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

# Start PHP server
echo "Starting PHP server on port 8080..."
php artisan serve --host=0.0.0.0 --port=8080 