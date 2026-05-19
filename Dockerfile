# ==========================================
# Stage 1: Build the Vue Frontend
# ==========================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ==========================================
# Stage 2: Create the production PHP environment
# ==========================================
FROM php:8.2-fpm-alpine

# Install system dependencies and build tools
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    autoconf \
    g++ \
    make

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Configure production OPcache
RUN docker-php-ext-install opcache
COPY <<EOF /usr/local/etc/php/conf.d/opcache.ini
[opcache]
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.revalidate_freq=0
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1
EOF

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application source code
COPY . .

# Copy compiled frontend assets from Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# Install production composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Optimizing route and config loading
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Set permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 and start fpm server
EXPOSE 9000
CMD ["php-fpm"]
