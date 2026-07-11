FROM tangramor/nginx-php8-fpm:php8.4.4_withoutNodejs

WORKDIR /var/www/html
COPY . /var/www/html

ENV WEBROOT=/var/www/html/public
ENV CREATE_LARAVEL_STORAGE=1

RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]