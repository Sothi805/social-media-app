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

# ensure directories exist and have proper permissions
RUN mkdir -p bootstrap/cache storage \
 && chown -R www-data:www-data bootstrap storage \
 && chmod -R 775 bootstrap storage

# now safely run composer post-install scripts (artisan is now here)
RUN composer install --no-dev --optimize-autoloader --no-interaction

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
