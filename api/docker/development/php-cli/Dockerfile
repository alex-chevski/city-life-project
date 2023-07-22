FROM php:8.2-cli-alpine

RUN apk update && apk add --upgrade --no-cache linux-headers && apk add --no-cache autoconf g++ make \
    && pecl install xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

RUN apk add postgresql-dev \
	&& docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
	&& docker-php-ext-install pdo_pgsql

RUN apk add --no-cache unzip

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d

ENV COMPOSER_ALLOW_SUPERUSER 1


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --version=2.5.8 --quiet --no-plugins --no-scripts \
	&& rm -rf /root/.composer/cache

WORKDIR /app
