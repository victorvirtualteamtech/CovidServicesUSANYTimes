FROM surnet/alpine-wkhtmltopdf:3.13.5-0.12.6-full as wkhtmltopdf
FROM alpine:3.13.5 as builder

RUN apk update

RUN apk add --no-cache php7 php7-pear php7-pdo php7-openssl php7-soap autoconf php7-dev curl gnupg gcc make \
    libc-dev openssl-dev g++ libstdc++ unixodbc unixodbc-dev \
    libxrender fontconfig freetype libx11 libxext libssl1.1 ca-certificates \
    ttf-ubuntu-font-family ttf-freefont

WORKDIR /var/www/html/symfony

ARG APP_ENVIRONMENT
ENV APP_ENVIRONMENT $APP_ENVIRONMENT

ARG SYMFONY_ENV
ENV SYMFONY_ENV $SYMFONY_ENV

COPY docker/php.ini /etc/php7/php.ini

RUN apk --no-cache add \
    nginx \
    php7-fpm \
    composer \
    php7-mbstring \
    php7-simplexml \
    php7-xml \
    php7-gd \
    php7-zip \
    php7-pdo \
    php7-dom \
    php7-ctype \
    php7-xmlreader \
    php7-xmlwriter \
    php7-fileinfo \
    php7-tokenizer \
    php7-json \
    php7-opcache \
    php7-curl \
    php7-soap \
    php7-session \
    busybox-initscripts \
    tini \
    tzdata \
    redis \
    supervisor

COPY . /var/www/html/symfony/

RUN composer install --no-dev --optimize-autoloader --prefer-dist

COPY ".env.$APP_ENVIRONMENT" /var/www/html/symfony/.env

RUN chmod 777 -R storage \
    &&	rm  /etc/php7/php-fpm.d/www.conf \
    &&  cp /usr/share/zoneinfo/America/Bogota /etc/localtime \
    &&  echo "America/Bogota" >  /etc/timezone \
    &&  apk del tzdata \
    &&  rm -rf \
    /var/cache/apk/*

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY docker/nginx/sites-enabled /etc/nginx/conf.d

COPY docker/www.conf /etc/php7/php-fpm.d/www.conf

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

COPY --from=wkhtmltopdf /bin/wkhtmltopdf /bin/wkhtmltopdf

COPY --from=wkhtmltopdf /bin/wkhtmltoimage /bin/wkhtmltoimage

COPY --from=wkhtmltopdf /bin/libwkhtmltox* /bin/

