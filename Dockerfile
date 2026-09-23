FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    sqlite-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_sqlite opcache \
    && rm -rf /var/cache/apk/*

WORKDIR /app

# --- Build stage for composer deps ---
FROM composer:2 AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist
COPY . .
RUN composer dump-autoload --optimize
RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache \
    && cp .env.example .env \
    && php artisan key:generate --force \
    && php artisan wayfinder:generate

# --- Build stage for frontend assets ---
FROM node:20-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
COPY --from=composer /app/resources/js/actions ./resources/js/actions
COPY --from=composer /app/resources/js/routes ./resources/js/routes
RUN SKIP_WAYFINDER=1 npm run build

# --- Final image ---
FROM base

COPY --from=composer /app /app
COPY --from=frontend /app/public/build /app/public/build

RUN mkdir -p /app/storage/app/private/invoices \
    /app/storage/framework/cache \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs \
    /app/bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/entrypoint.sh /app/entrypoint.sh
RUN chmod +x /app/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/app/entrypoint.sh"]
