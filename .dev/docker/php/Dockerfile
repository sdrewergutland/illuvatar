FROM php:8.2-apache

RUN sed -ri -e 's!/var/www/html!${DOCKER_PHP_APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${DOCKER_PHP_APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf