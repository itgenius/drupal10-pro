FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) intl pdo_mysql zip gd opcache

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install redis && docker-php-ext-enable redis
COPY php.ini /usr/local/etc/php/conf.d/zz-php.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/zz-xdebug.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer