FROM php:8.1-fpm

# INSTALL LIBS
RUN apt-get update
RUN apt-get install --yes --force-yes gzip wget cron g++ gettext libicu-dev openssl libc-client-dev libkrb5-dev libxml2-dev libfreetype6-dev libgd-dev libmcrypt-dev bzip2 libbz2-dev libtidy-dev libcurl4-openssl-dev libz-dev libmemcached-dev libxslt-dev poppler-utils xfonts-75dpi wkhtmltopdf nano libpq-dev software-properties-common postgresql postgresql-client postgresql-contrib libzip-dev libmagickcore-dev

# INSTALL AND ENABLE ALL PHP EXTENSIONS
RUN docker-php-ext-install pcntl \
bcmath \
bz2 \
calendar \
dba \
exif \
fileinfo \
gd \
gettext \
sockets \
pgsql \
pdo_pgsql \
zip

RUN apt-get update && apt-get install -y \
    imagemagick libmagickwand-dev --no-install-recommends \
    && pecl install imagick 
RUN docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr
RUN docker-php-ext-configure hash --with-mhash
    
RUN docker-php-ext-enable imagick \
pgsql \
pdo_pgsql \
zip

# INSTALL COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --1

# INSTALL NODE
RUN apt install --yes nodejs npm

WORKDIR /var/www/html
COPY .. .

RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-scripts --no-interaction

RUN npm install && npm run dev

#RUN php artisan migrate
#RUN php artisan db:seed

EXPOSE 8000



