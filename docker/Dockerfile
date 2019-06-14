FROM php:fpm-alpine

# Apply custom php configuration
COPY conf/php.ini /usr/local/etc/php

# Install required php dependencies
RUN docker-php-ext-install opcache pdo_mysql
