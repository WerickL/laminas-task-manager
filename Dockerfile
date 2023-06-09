#
# Use this dockerfile to run api-tools.
#
# Start the server using docker-compose:
#
#   docker-compose build
#   docker-compose up
#
# You can install dependencies via the container:
#
#   docker-compose run api-tools composer install
#
# You can manipulate dev mode from the container:
#
#   docker-compose run api-tools composer development-enable
#   docker-compose run api-tools composer development-disable
#   docker-compose run api-tools composer development-status
#
# OR use plain old docker 
#
#   docker build -f Dockerfile-dev -t api-tools .
#   docker run -it -p "8080:80" -v $PWD:/var/www api-tools
#
FROM composer:2.3.5 AS get-composer
FROM php:8.0-apache

WORKDIR /var/www
ENV APACHE_RUN_USER=www-data
ADD --chown=1000:1000 . /var/www/

RUN apt-get update \
 && apt-get install -y git libzip-dev libicu-dev \
 && docker-php-ext-install zip \
 && docker-php-ext-configure intl \
 && docker-php-ext-install intl \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && echo "AllowEncodedSlashes On" >> /etc/apache2/apache2.conf

COPY --from=get-composer /usr/bin/composer /usr/local/bin/composer

RUN mkdir -p /var/www/data/cache \
 && chown -R www-data:www-data /var/www/data/cache \
 && mkdir -p /var/www/public/api-tools \
 && chown -R www-data:www-data /var/www/public/api-tools

USER www-data

