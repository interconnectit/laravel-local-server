#
# Proxy
#
FROM traefik:3.6.2 as proxy

WORKDIR /code

COPY conf/traefik.toml /etc/traefik

RUN set -ex \
        && mkdir -p /etc/traefik/ssl \
        && wget -q -O mkcert https://github.com/FiloSottile/mkcert/releases/download/v1.4.4/mkcert-v1.4.4-linux-amd64 \
        && chmod +x mkcert \
        && ./mkcert -install \
        && ./mkcert -cert-file /etc/traefik/ssl/default.crt -key-file /etc/traefik/ssl/default.key localhost "*.localhost" 127.0.0.1 ::1 localtest.me "*.localtest.me"

# COPY conf/traefik.toml /etc/traefik/traefik.toml

#
# Frontend
#
FROM nginx:1.27-alpine as frontend

COPY conf/default.conf /etc/nginx/conf.d

#
# Backend
#
FROM php:8.3-fpm-alpine as backend

WORKDIR /code

RUN apk add --no-cache --virtual .persistent-deps icu freetype libjpeg-turbo libpng zlib
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev freetype-dev libjpeg-turbo-dev libpng-dev zlib-dev

RUN apk add icu-dev

RUN docker-php-ext-install bcmath opcache pdo_mysql pcntl intl

RUN	docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-enable gd

RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif

RUN docker-php-ext-configure intl
RUN docker-php-ext-enable intl

RUN docker-php-ext-configure pcntl
RUN docker-php-ext-enable pcntl

RUN pear update-channels
RUN pecl channel-update pecl.php.net

RUN pecl install redis
RUN docker-php-ext-enable redis

RUN apk del .build-deps

COPY conf/php.ini /usr/local/etc/php
COPY conf/opcache.ini /usr/local/etc/php/conf.d

#
# Artisan
#
FROM php:8.3-cli-alpine as artisan

WORKDIR /code

COPY scheduler /usr/local/bin

RUN apk add --no-cache --virtual .persistent-deps freetype libjpeg-turbo libpng zlib
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS freetype-dev libjpeg-turbo-dev libpng-dev zlib-dev

RUN docker-php-ext-install bcmath pdo_mysql pcntl

RUN	docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-enable gd

RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif

RUN docker-php-ext-configure pcntl
RUN docker-php-ext-enable pcntl

RUN pear update-channels
RUN pecl channel-update pecl.php.net

RUN pecl install redis
RUN docker-php-ext-enable redis

RUN apk del .build-deps
