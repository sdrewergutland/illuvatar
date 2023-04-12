FROM php:8.2-apache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get -y update && apt-get -y install \
	git \
    zip \
    unzip

#xdebug
RUN pecl install xdebug  && docker-php-ext-enable xdebug
RUN mkdir -p /usr/local/etc/xdebug
COPY ./etc/ /usr/local/etc/xdebug/
COPY ./etc/xdebug-off.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN a2enmod rewrite
COPY ./etc/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./etc/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql

RUN pecl install redis && docker-php-ext-enable redis

RUN apt-get update && apt-get install -y mariadb-client && rm -rf /var/lib/apt