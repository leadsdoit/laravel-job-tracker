FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure zip \
 && docker-php-ext-install zip

RUN pecl install xdebug \
 && docker-php-ext-enable xdebug

RUN { \
      echo "; Xdebug base config"; \
      echo "xdebug.mode=develop,debug"; \
      echo "xdebug.start_with_request=yes"; \
      echo "xdebug.client_host=host.docker.internal"; \
      echo "xdebug.client_port=9003"; \
      echo "xdebug.log_level=0"; \
    } > /usr/local/etc/php/conf.d/99-xdebug.ini


ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
