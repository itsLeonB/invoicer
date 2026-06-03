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

# --- Build stage for frontend assets ---
FROM node:20-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN SKIP_WAYFINDER=1 npm run build

# --- Build stage for composer deps ---
FROM composer:2 AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist
COPY . .
RUN composer dump-autoload --optimize

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

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
