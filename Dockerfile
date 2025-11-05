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

# working directory
WORKDIR /var/www/html

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy app source
COPY . .

# bring in built frontend assets from previous stage
COPY --from=frontend /app/public/build ./public/build

# install PHP dependencies (no-dev, optimized)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# set permissions (so www-data can write storage/cache)
RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
