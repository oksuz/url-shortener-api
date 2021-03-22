FROM php:7.4-fpm

RUN apt-get update && apt-get install -y ca-certificates wget unzip wait-for-it
RUN docker-php-ext-install mysqli pdo pdo_mysql json iconv ctype

RUN wget https://getcomposer.org/installer -O composer-setup.php

RUN php composer-setup.php \
    && chmod +x composer.phar \
    && mv composer.phar /usr/local/bin/composer


WORKDIR /opt/app

COPY composer.json .
COPY composer.lock .
RUN composer install

VOLUME ["/opt/app/vendor"]

COPY . .

RUN php bin/phpunit

ENV APP_ENV prod
ENV APP_DEBUG 0

# default environment variables
ENV DB_PASSWORD root
ENV DB_USER root
ENV DB_HOST mysql
ENV DB_PORT 3306
ENV DB_NAME url_shortener
ENV BITLY_TOKEN bitly-token
ENV APP_SECRET s3cr3t

RUN php bin/console cache:clear
RUN php bin/console cache:warmup

RUN chown -R www-data:www-data /opt/app/var