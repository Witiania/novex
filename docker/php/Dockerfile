FROM php:8.2-fpm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    apt-get install -y libpq-dev nano libzip-dev unzip && \
    docker-php-ext-install pdo pdo_pgsql pgsql zip

WORKDIR /app

COPY . .

CMD php-fpm -F;