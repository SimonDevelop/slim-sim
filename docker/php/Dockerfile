FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        apt-utils \
        libmcrypt-dev \
        libpq-dev \
        libicu-dev \
        libvpx-dev \
        libjpeg-dev \
        libpng-dev \
        libxpm-dev \
        zlib1g-dev \
        libfreetype6-dev \
        libxml2-dev \
        libexpat1-dev \
        libbz2-dev \
        libgmp3-dev \
        libldap2-dev \
        unixodbc-dev \
        libsqlite3-dev \
        libaspell-dev \
        libsnmp-dev \
        libpcre3-dev \
        libtidy-dev \
        default-libmysqlclient-dev \
        libsqlite3-dev \
        libssl-dev \
        libcurl3-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        openssl \
        zip \
        unzip

RUN docker-php-ext-install iconv json mbstring opcache intl bz2 mysqli pdo_mysql pdo_sqlite phar curl ftp session simplexml tokenizer xml xmlrpc zip
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

WORKDIR /var/www/slim-sim

CMD ["php-fpm"]
