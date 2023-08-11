ARG PHP_VERSION="8.1"

FROM composer:latest as composer

COPY database/ database/
COPY tests/ tests/
COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer selfupdate

# add --no-dev for production
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

COPY  --chown=www-data:www-data . /app

RUN composer dump-autoload --optimize --classmap-authoritative

FROM php:${PHP_VERSION}-fpm-alpine

RUN apk update && \
    apk add \
        curl \
        nginx \
        supervisor \
        libintl \
        libjpeg-turbo-dev \
        libpng-dev \
        libxml2-dev \
        libmcrypt-dev \
        libzip-dev \
        libwebp-dev \
        freetype-dev \
        icu-dev \
        zip \
        gd \
    	openssl-dev \
        $PHPIZE_DEPS

RUN docker-php-ext-configure zip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install mysqli  \
    pdo  \
    pdo_mysql  \
    exif  \
    bcmath  \
    zip  \
    intl \
    opcache

RUN docker-php-ext-enable opcache

#RUN pecl install swoole
#RUN docker-php-ext-enable swoole

COPY docker/app/php.ini  $PHP_INI_DIR/conf.d/

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=composer /app/ /var/www/html/

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

COPY docker/app/entrypoint.sh /usr/local/bin/

COPY docker/app/supervisor.conf /etc/supervisor/conf.d/worker.conf

RUN chmod +x /usr/local/bin/entrypoint.sh

RUN chown -R www-data:www-data /var/www/html/storage/*

RUN chmod -R o+w storage/
RUN chmod -R o+w bootstrap/cache/

ENTRYPOINT ["entrypoint.sh"]

