FROM php:8.1-fpm

# install git
RUN apt-get update && \
    apt-get install -y --no-install-recommends git

RUN usermod -u 1000 www-data

#install some base extensions
RUN apt-get update && \
    apt-get install -y \
        build-essential \
        libssl-dev \
        zlib1g-dev \
        libzip-dev \
        libwebp-dev \
        zip \
        unzip \
        libpng-dev \
        libjpeg-dev \
        exiftool \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libicu-dev \
        libpq-dev \
        libxpm-dev \
        libvpx-dev \
        mariadb-client \
        libxml2-dev \
        wget

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --version=1.10.16 --install-dir=/usr/local/bin --filename=composer
RUN composer --version


WORKDIR /var/www/html

# Installing composer dependencies
FROM composer AS dependencies

WORKDIR /app

COPY composer.* /app/
RUN composer install -n \
    --optimize-autoloader \
    --no-progress \
    --no-suggest \
    --prefer-dist \
    --no-dev \
    --ignore-platform-reqs

COPY . /app/
RUN composer dump-autoload --no-dev --classmap-authoritative

# Configure GD
RUN docker-php-ext-configure gd \
    --with-freetype --with-webp --with-jpeg \
   && docker-php-ext-install gd

RUN docker-php-ext-install -j$(nproc) \
   exif \
   bcmath \
   intl \
   pcntl \
   mysqli \
   pdo \
   pdo_mysql \
   pdo_pgsql \
   soap \
   opcache

# Install Node
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - 
RUN apt-get install -y nodejs

###
### Install yarn package manager for webpack-encore
###
RUN npm install -g yarn

### Set working directory and copy
WORKDIR /var/www/html
COPY . /var/www/html/
 
EXPOSE 9000

CMD ["php-fpm"]
