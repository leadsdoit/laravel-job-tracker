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
      echo "zend_extension=/usr/local/lib/php/extensions/$(php -r 'echo PHP_ZTS ? \"no-debug-zts-\" : \"no-debug-non-zts-\";');$(php -r 'echo PHP_VERSION_ID>=80300?\"20230831\":\"\";')/xdebug.so"; \
      echo "xdebug.log_level=0"; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
