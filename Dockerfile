FROM php:8.0-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 80 8000

RUN mkdir -p /app/web
WORKDIR /app/web

# git, unzip & zip are for composer
RUN apt-get update -qq && \
    apt-get install -qy \
    git \
    gnupg \
    libicu-dev \
    libzip-dev \
    libssl-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libpq-dev \
    unzip \
    zip \
    zlib1g-dev && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# PHP support GD
# For PHP v8 only
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd

# PHP extensions
RUN docker-php-ext-install -j$(nproc) intl opcache

# PHP support mysql/mariadb
RUN docker-php-ext-install mysqli pdo_mysql

# PHP support postgres
RUN docker-php-ext-install pdo_pgsql

# PHP Support de apcu
RUN pecl install apcu && docker-php-ext-enable apcu

# PHP Support de redis
RUN pecl install redis && docker-php-ext-enable redis

# Xdebug (disabled by default, but installed if required)
# RUN pecl install xdebug-2.9.7 && docker-php-ext-enable xdebug
# ADD conf/xdebug.ini /usr/local/etc/php/conf.d/
RUN pecl install xdebug-3.1.3 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


# PHP configuration
COPY conf/php.ini /usr/local/etc/php/conf.d/app.ini

# Apache
COPY errors /errors
COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY conf/apache.conf /etc/apache2/conf-available/z-app.conf

RUN a2enmod rewrite remoteip && \
    a2enconf z-app
