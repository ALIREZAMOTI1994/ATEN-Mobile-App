FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --no-progress --prefer-dist \
    && composer clear-cache

# php-fpm + nginx in a single image, so the API deploys as one container on
# any single-service host (Liara, Railway, Fly.io, ...) while docker-compose
# keeps using the same image for local/self-hosted runs.
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        nginx \
        supervisor \
    && docker-php-ext-install \
        pdo_mysql \
        intl \
        zip \
        opcache \
    && apk del icu-dev libzip-dev oniguruma-dev

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh \
    && mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080
ENTRYPOINT ["docker/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
