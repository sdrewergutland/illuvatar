FROM php:8.2-apache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get -y update
RUN apt-get -y install \
	git \
    zip \
    unzip

RUN pecl install \
    xdebug

RUN docker-php-ext-enable \
    xdebug

RUN sed -ri -e 's!/var/www/html!${DOCKER_PHP_APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${DOCKER_PHP_APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# xdebug
RUN mkdir -p /usr/local/etc/xdebug
COPY ./etc/xdebug-debug.ini /usr/local/etc/xdebug/xdebug-debug.ini
COPY ./etc/xdebug-default.ini /usr/local/etc/xdebug/xdebug-default.ini
COPY ./etc/xdebug-off.ini /usr/local/etc/xdebug/xdebug-off.ini
COPY ./etc/xdebug-profile.ini /usr/local/etc/xdebug/xdebug-profile.ini
COPY ./etc/xdebug-trace.ini /usr/local/etc/xdebug/xdebug-trace.ini

COPY ./etc/xdebug-off.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini