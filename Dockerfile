FROM php:8.3-cli

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
