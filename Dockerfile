FROM php:8.2-fpm

# Install system dependencies - add here if needed
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

CMD ["php-fpm"]
