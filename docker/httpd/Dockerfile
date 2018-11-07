FROM php:7.2-apache

RUN a2enmod rewrite && \
    sed -i "s/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html/" /etc/apache2/sites-enabled/000-default.conf && \
    apt-get update && \
    apt-get install -y libzip-dev libicu-dev libxml2-dev libcurl4-openssl-dev libjpeg-dev libpng-dev && \
    docker-php-ext-install mbstring zip json && pecl install -o -f xdebug && docker-php-ext-enable xdebug

WORKDIR /var/www/html