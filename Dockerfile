# syntax=docker/dockerfile:1
FROM php:8.2-fpm-alpine as base

# Install system dependencies
RUN apk add --no-cache nginx supervisor curl git sqlite sqlite-dev libpng libpng-dev libjpeg-turbo-dev libwebp-dev libxpm-dev freetype-dev oniguruma-dev icu-dev zlib-dev libzip-dev libxml2-dev nodejs npm

# PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite mbstring zip exif pcntl bcmath intl xml gd

# Configure PHP for production
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN echo "max_execution_time = 300" > /usr/local/etc/php/conf.d/execution-time.ini

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build assets
RUN npm install && npm run build

# Generate application key if not exists
RUN php artisan key:generate --force || true

# Create SQLite directory and file
RUN mkdir -p /temp && touch /temp/korporatio_api && chmod -R 777 /temp

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port for Railway
EXPOSE 8080

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"] 