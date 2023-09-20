FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip

RUN pecl install xdebug

RUN docker-php-ext-install zip
RUN docker-php-ext-enable xdebug

WORKDIR /var/www/html

COPY composer.json composer.json

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
