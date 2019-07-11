#
# Proxy
#
FROM traefik as proxy

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
FROM php:fpm-alpine as backend

WORKDIR /code

RUN apk add --no-cache --virtual .persistent-deps libjpeg-turbo libpng libwebp freetype
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS libjpeg-turbo-dev libpng-dev libwebp-dev freetype-dev

# Installing GD
RUN docker-php-ext-configure gd \
    --with-jpeg-dir=/usr/include \
    --with-png-dir=/usr/include \
    --with-webp-dir=/usr/include \
    --with-freetype-dir=/usr/include

RUN docker-php-ext-install bcmath gd opcache pdo_mysql

RUN apk del .build-deps

COPY conf/php.ini /usr/local/etc/php
COPY conf/opcache.ini /usr/local/etc/php/conf.d

#
# Artisan
#
FROM php:cli-alpine as artisan

WORKDIR /code

RUN apk add --no-cache --virtual .persistent-deps libzip
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS libzip-dev

RUN docker-php-ext-install bcmath pdo_mysql

# Installing zip
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install zip

RUN apk del .build-deps
