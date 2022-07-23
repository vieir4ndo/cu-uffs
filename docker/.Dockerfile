FROM php:8.1-fpm

RUN apt-get update
RUN apt-get install --yes --force-yes gzip wget cron g++ gettext libicu-dev openssl libc-client-dev libkrb5-dev libxml2-dev libfreetype6-dev libgd-dev libmcrypt-dev bzip2 libbz2-dev libtidy-dev libcurl4-openssl-dev libz-dev libmemcached-dev libxslt-dev poppler-utils xfonts-75dpi wkhtmltopdf nano libpq-dev


RUN apt-get update && apt-get install -y software-properties-common postgresql postgresql-client postgresql-contrib

#PHP CONFIGURATION
#RUN docker-php-ext-enable postgresql
RUN docker-php-ext-install pcntl \
bcmath \
bz2 \
calendar \
dba \
exif \
fileinfo

RUN docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr

RUN docker-php-ext-install gd
RUN docker-php-ext-install gettext

RUN docker-php-ext-configure hash --with-mhash
RUN docker-php-ext-install sockets

RUN apt-get install --yes --force-yes libmagickwand-dev libmagickcore-dev
RUN yes '' | pecl install -f imagick
RUN docker-php-ext-enable imagick

RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo_pgsql

RUN docker-php-ext-enable pgsql
RUN docker-php-ext-enable pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --1

WORKDIR /var/www/html
#COPY ../.env .env
COPY .. .

RUN COMPOSER_MEMORY_LIMIT=-1 composer install
#RUN php artisan migrate
#RUN php artisan db:seed

EXPOSE 8000



