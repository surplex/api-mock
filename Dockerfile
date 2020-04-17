FROM php:7.3-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN usermod -u 1000 www-data

RUN apt-get update && apt-get install -y \
curl \
zlib1g-dev \
git \
libxml2-dev \
libmemcached11 \
libmemcached-dev \
libmcrypt-dev \
gnupg \
unzip \
sqlite3 \
libsqlite3-0 \
libsqlite3-dev \
libxrender1 \
libfontconfig1 \
libzip-dev && rm -rf /var/lib/apt/lists/*

RUN echo 'date.timezone = "Europe/Berlin"' >> /usr/local/etc/php/php.ini \
    && echo "session.auto_start = off" >> /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN docker-php-ext-install zip opcache xmlrpc soap pdo pdo_mysql calendar bcmath
RUN pecl install memcached-3.1.3 sqlite3
RUN docker-php-ext-enable memcached

WORKDIR /usr/src/app
COPY . /usr/src/app
VOLUME ["/usr/src/app/db"]
RUN composer install

ENTRYPOINT ["docker/entrypoint.sh"]