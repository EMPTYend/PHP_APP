FROM php:8.2-apache

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    libpcre3-dev

# Установка расширений PHP
RUN docker-php-ext-install pdo pdo_mysql zip \
    && pecl install apcu \
    && docker-php-ext-enable apcu

# Включение mod_rewrite для Apache
RUN a2enmod rewrite

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html