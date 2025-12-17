FROM php:8.3-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
 && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    intl \
    bcmath \
    gd \
 && rm -rf /var/lib/apt/lists/*

# Redis extension
RUN pecl install redis \
 && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Composer config
ENV COMPOSER_HOME=/home/laravel/.composer

# Working directory
WORKDIR /var/www

USER root

EXPOSE 9000

CMD ["php-fpm"]
