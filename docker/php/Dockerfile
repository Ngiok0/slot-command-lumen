FROM php:8.0-fpm

COPY ./docker/php/php.override.ini /usr/local/etc/php/conf.d

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y \
    git \
    curl \
    gnupg2 \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    pkg-config \
    zip \
    unzip

RUN addgroup --gid 1000 www
RUN adduser --gid 1000 --shell /bin/sh www

RUN docker-php-ext-install mbstring exif pcntl bcmath gd

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer -V

COPY ./ /var/www
COPY --chown=www:www ./ /var/www

WORKDIR /var/www
USER www
