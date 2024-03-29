FROM php:8.0-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN usermod -u 1000 www-data

RUN echo 'date.timezone = "Europe/Berlin"' >> /usr/local/etc/php/php.ini \
    && echo "session.auto_start = off" >> /usr/local/etc/php/php.ini

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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN docker-php-ext-install zip opcache xml soap pdo pdo_mysql calendar bcmath
RUN pecl install memcached sqlite3
RUN docker-php-ext-enable memcached

WORKDIR /usr/src/app
COPY . /usr/src/app
RUN chmod 777 /usr/src/app/var/logs
VOLUME ["/usr/src/app/db"]
RUN composer install

RUN chmod +x docker/entrypoint.sh

ENTRYPOINT ["docker/entrypoint.sh"]
