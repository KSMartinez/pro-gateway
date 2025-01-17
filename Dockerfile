FROM php:8.1-fpm-alpine
#ENV http_proxy http://logan.actimage.int:80
#ENV https_proxy https://logan.actimage.int:80

#install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk --update --no-cache add git

#RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-install pdo pdo_mysql
# for mysqli if you want
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN apk add icu-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-enable intl


WORKDIR /var/www/


EXPOSE 9000


# vim:set ft=dockerfile:
