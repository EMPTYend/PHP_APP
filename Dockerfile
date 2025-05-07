FROM php:8.2.28-apache-bookworm

# Устанавливаем системные зависимости и расширения PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Включаем необходимые модули Apache
RUN a2enmod rewrite headers

# Настройка Apache и DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Настройка PHP для загрузки файлов
RUN echo "file_uploads = On" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 22M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_file_uploads = 20" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_tmp_dir = /tmp/php_uploads" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "session.save_path = /tmp/php_sessions" >> /usr/local/etc/php/conf.d/uploads.ini \
    && mkdir -p /tmp/php_uploads \
    && mkdir -p /tmp/php_sessions \
    && chmod -R 777 /tmp/php_uploads \
    && chmod -R 777 /tmp/php_sessions

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем файлы
COPY --chown=www-data:www-data . /var/www/html/

# Устанавливаем composer, если существует composer.json
RUN if [ -f "composer.json" ]; then \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');" && \
    composer install --no-dev --optimize-autoloader; \
    fi

# Оптимизация конфигурации PHP для production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    echo "expose_php = Off" >> "$PHP_INI_DIR/php.ini"

# Открываем порт для Apache
EXPOSE 80

# Запуск Apache в foreground
CMD ["apache2-foreground"]
