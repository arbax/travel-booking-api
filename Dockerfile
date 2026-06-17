FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage