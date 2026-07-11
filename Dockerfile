FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader --no-interaction
COPY . .
RUN composer dump-autoload --optimize --no-dev

FROM php:8.4-cli-alpine

RUN apk add --no-cache postgresql-dev libzip-dev \
 && docker-php-ext-install pdo pdo_pgsql mbstring bcmath zip

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

EXPOSE 10000
ENTRYPOINT ["/docker-entrypoint.sh"]