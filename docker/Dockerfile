#
# Proxy
#
FROM traefik:alpine as proxy

WORKDIR /code

COPY conf/traefik.toml /etc/traefik

#
# Frontend
#
FROM nginx:alpine as frontend

WORKDIR /code

COPY conf/default.conf /etc/nginx/conf.d

#
# Backend
#
FROM php:8.2-fpm-alpine as backend

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
FROM php:8.2-cli-alpine as artisan

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
