FROM tangramor/nginx-php8-fpm:php8.3.4_node21.7.2

WORKDIR /var/www/html
COPY . /var/www/html

ENV WEBROOT=/var/www/html/public
ENV CREATE_LARAVEL_STORAGE=1

# A placeholder .env so Composer's package:discover step has something to
# bootstrap against during the build. Real values come from Render's actual
# environment variables at runtime and take priority over this file.
RUN cp .env.example .env

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm install

RUN npm run build

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]
