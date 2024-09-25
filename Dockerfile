FROM composer:2.2 AS composer

FROM php:8.2-fpm-alpine3.19 as php

RUN apk --update --no-cache add bash



COPY --from=composer /usr/bin/composer /usr/bin/composer


RUN umask 000


COPY entrypoint.sh /usr/local/bin/entrypoint

RUN chmod a+x /usr/local/bin/entrypoint

ENTRYPOINT ["entrypoint"]

WORKDIR /var/www/test
EXPOSE 8080
