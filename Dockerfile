# Используем официальный образ PHP с Apache
FROM php:8.2-apache

# Устанавливаем необходимые расширения PHP (например, для работы с MySQL)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Включаем mod_rewrite для Apache
RUN a2enmod rewrite

# Добавление настройки ServerName в конфигурацию Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


# Настройка DocumentRoot (чтобы Apache знал, где искать публичный файл)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Копируем файлы проекта в контейнер
COPY . /var/www/html/

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Открываем порт для Apache
EXPOSE 80
