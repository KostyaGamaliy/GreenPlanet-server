FROM php:8.1-cli-alpine3.15

ARG CURRENT_USER

RUN addgroup -g 1000 $CURRENT_USER \
    && adduser -D -u 1000 -G $CURRENT_USER $CURRENT_USER

RUN apk add --no-cache --virtual build-dependencies icu-dev libxml2-dev $PHPIZE_DEPS \
    && docker-php-ext-install -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) mysqli opcache pdo_mysql xml fileinfo pdo exif\
    && pecl install redis \
    && docker-php-ext-enable redis

RUN chown -R $CURRENT_USER:$CURRENT_USER /var/www
USER $CURRENT_USER
#COPY --chown=www-data:www-data . /var/www/

ENTRYPOINT ["artisan/migrate.sh"]

