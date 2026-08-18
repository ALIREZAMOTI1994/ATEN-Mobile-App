FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --no-progress --prefer-dist \
    && composer clear-cache

FROM php:8.4-fpm-alpine
WORKDIR /var/www/html

RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
    && docker-php-ext-install \
        pdo_mysql \
        intl \
        zip \
        opcache \
    && apk del icu-dev libzip-dev oniguruma-dev

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN addgroup -g 1000 aten \
    && adduser -G aten -u 1000 -D aten \
    && chown -R aten:aten /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh

USER aten

EXPOSE 9000
ENTRYPOINT ["docker/entrypoint.sh"]
CMD ["php-fpm"]
