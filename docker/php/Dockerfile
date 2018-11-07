FROM php:7.2-cli

RUN apt-get update && \
    apt-get install -y libzip-dev libicu-dev libxml2-dev libcurl4-openssl-dev libjpeg-dev libpng-dev && \
    docker-php-ext-install mbstring zip json && pecl install -o -f xdebug && docker-php-ext-enable xdebug