FROM php:8.2-apache

# Установка необходимых расширений PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Включение mod_rewrite для Apache
RUN a2enmod rewrite

# Копирование конфигурации Apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html