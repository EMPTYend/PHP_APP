# Используем официальный образ PHP с Apache (обновленная версия)
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

# Настройка Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    mkdir -p /var/www/html/public && \
    chown -R www-data:www-data /var/www/html

# Установка DocumentRoot (используем новый формат ENV)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Копируем только необходимые файлы (многоступенчатая сборка)
COPY --chown=www-data:www-data . /var/www/html/

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Обновляем композер
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