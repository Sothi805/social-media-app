# ───────────────────────────────
# Stage 1: Build frontend assets
# ───────────────────────────────
FROM node:25-alpine AS frontend

WORKDIR /app

# Copy dependency definitions first (for better caching)
COPY package*.json vite.config.* tailwind.config.* postcss.config.* ./
RUN npm ci

# Copy only source files and build assets
COPY resources ./resources
COPY public ./public
RUN npm run build


# ───────────────────────────────
# Stage 2: PHP runtime (Laravel)
# ───────────────────────────────
FROM php:8.4-fpm

# Install required packages and PHP extensions
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first (for better caching)
COPY composer.json composer.lock ./

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create Laravel directories and set correct permissions before install
RUN mkdir -p bootstrap/cache storage \
 && chown -R www-data:www-data bootstrap storage \
 && chmod -R 775 bootstrap storage \
 && composer install --no-dev --optimize-autoloader --no-interaction

# Copy application source code
COPY . .

# Bring in built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Final permission fix (for any new files)
RUN chown -R www-data:www-data /var/www/html

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
