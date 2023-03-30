FROM php:8.2-apache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get -y update
RUN apt-get -y install \
	git \
    zip \
    unzip

RUN sed -ri -e 's!/var/www/html!${DOCKER_PHP_APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${DOCKER_PHP_APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf