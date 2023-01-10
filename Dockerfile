ARG DOCKER_IMAGES_TAG
FROM crpdlusy.azurecr.io/alpine-php81-apache:${DOCKER_IMAGES_TAG}

WORKDIR /var/www/html

COPY config config
COPY data data
COPY module module
COPY public public
COPY vendor vendor
COPY console console
COPY consoled consoled
COPY build.xml build.xml
COPY startup.sh startup.sh
COPY clear_cache clear_cache

RUN rm -f data/cache/module-*
RUN rm -f config/autoload/*local.php
RUN rm -f config/development.config.php
