FROM {{ image }}

RUN a2enmod rewrite && \
    sed -i "s/DocumentRoot \/var\/www\/html/DocumentRoot {{ docroot_escaped }}/" /etc/apache2/sites-enabled/000-default.conf && \
    apt-get update && \
    apt-get install -y libzip-dev libicu-dev libxml2-dev libcurl4-openssl-dev libjpeg-dev libpng-dev && \
    {{ extensions }}

WORKDIR {{ docroot }}