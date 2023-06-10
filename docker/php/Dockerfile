FROM surnet/alpine-wkhtmltopdf:3.16.2-0.12.6-full as wkhtmltopdf

FROM php:8.1-fpm-alpine3.15

ARG CURRENT_USER

RUN apk add --update linux-headers

RUN addgroup -g 1000 $CURRENT_USER \
    && adduser -D -u 1000 -G $CURRENT_USER $CURRENT_USER

RUN apk update

RUN apk add --no-cache --virtual build-dependencies icu-dev libxml2-dev  libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev  $PHPIZE_DEPS \
    && docker-php-ext-install -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) mysqli opcache pdo_mysql xml fileinfo pdo exif\
    && pecl install redis \
    && docker-php-ext-enable redis

RUN apk add --no-cache libpng libpng-dev libjpeg-turbo-dev libwebp-dev zlib-dev libxpm-dev libzip-dev zip  \
    && docker-php-ext-configure gd --enable-gd --with-webp --with-jpeg --with-xpm --with-freetype \
    && docker-php-ext-install gd zip

WORKDIR /var/www
RUN chown -R $CURRENT_USER:$CURRENT_USER /var/www

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \
    && echo "zend_extension=xdebug" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=192.168.1.104" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini

USER $CURRENT_USER
EXPOSE 9000

