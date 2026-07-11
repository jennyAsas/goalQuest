FROM tangramor/nginx-php8-fpm:php8.4.1_node21.7.2

WORKDIR /var/www/html
COPY . /var/www/html

ENV WEBROOT=/var/www/html/public
ENV CREATE_LARAVEL_STORAGE=1

RUN composer install --no-dev --optimize-autoloader --no-interaction \
 && npm install \
 && npm run build

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]