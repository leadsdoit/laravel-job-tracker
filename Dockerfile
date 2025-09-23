FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure zip \
 && docker-php-ext-install zip

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
