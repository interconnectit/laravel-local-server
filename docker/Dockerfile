FROM nginx:alpine as nginx

# Add custom vhost
COPY conf/vhost.conf /etc/nginx/conf.d

FROM php:fpm-alpine as php

# Apply custom php configuration
COPY conf/php.ini /usr/local/etc/php

# Install required php dependencies
RUN docker-php-ext-install opcache pdo_mysql
