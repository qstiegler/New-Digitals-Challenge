FROM php:8.3-fpm-alpine

# Add required packages
# git to run Symfony Flex recipes
# postgresql-client as base for postgres integration
# curl to install composer
# make binutils linux-headers to make the build work
RUN apk add --update --no-cache \
    autoconf \
    g++ \
    git \
    make \
    binutils \
    curl \
    linux-headers \
    postgresql-client

RUN pecl install xdebug-3.3.0 && docker-php-ext-enable xdebug

# Add php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

# Postgres
RUN install-php-extensions pgsql
RUN install-php-extensions pdo_pgsql

# Zip
RUN install-php-extensions zip

# Image handling
RUN install-php-extensions gd

# Performance
RUN install-php-extensions opcache

# Debugging
RUN install-php-extensions xdebug

# Ids package
RUN install-php-extensions uuid

# Multithreaded Psalm
RUN install-php-extensions pcntl
RUN install-php-extensions posix

# Requirements of vendor packages
RUN install-php-extensions mbstring
RUN install-php-extensions intl

RUN echo "" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    ln -s $(composer config --global home) /root/composer
ENV PATH=$PATH:/root/composer/vendor/bin COMPOSER_ALLOW_SUPERUSER=1

RUN apk del curl make binutils linux-headers
RUN rm -rf /var/cache/apk/*

# Add configuration
ADD php.ini /etc/php/conf.d/
ADD php.ini /etc/php/cli/conf.d/
ADD php.ini /usr/local/etc/php
ADD php-fpm.conf /etc/php/php-fpm.d/
ADD docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www/html

CMD ["php-fpm", "-F"]
