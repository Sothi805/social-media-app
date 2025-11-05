# ───────────────────────────────
# Stage 1: Build assets with Node
# ───────────────────────────────
FROM node:25-alpine AS frontend

WORKDIR /app

# copy only files needed for npm install first (better caching)
COPY package*.json vite.config.* tailwind.config.* postcss.config.* ./
RUN npm ci

# then copy source and build
COPY resources ./resources
COPY public ./public
RUN npm run build


# ───────────────────────────────
# Stage 2: PHP-FPM (runtime)
# ───────────────────────────────
FROM php:8.4-fpm

# system & PHP deps
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# bring in Composer from official image (do this before composer install)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy only composer files first (for better caching)
COPY composer.json composer.lock ./

# install dependencies without running scripts (artisan not available yet)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# now copy full Laravel app
COPY . .

# bring in built frontend assets
COPY --from=frontend /app/public/build ./public/build

# ensure Laravel cache and storage directories exist and are writable
RUN mkdir -p bootstrap/cache \
    storage/framework/cache/data \
 && chmod -R 775 bootstrap storage \
 && chown -R www-data:www-data bootstrap storage

# install dependencies WITHOUT running artisan scripts yet
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# copy entire Laravel source after dependencies
COPY . .

# run post-install scripts manually (artisan commands)
RUN mkdir -p bootstrap/cache storage/framework/cache/data \
 && chmod -R 775 bootstrap storage \
 && chown -R www-data:www-data bootstrap storage \
 && composer run-script post-install-cmd || true \
 && php artisan package:discover --ansi || true

USER www-data

EXPOSE 9000
CMD ["php-fpm"]

