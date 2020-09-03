ARG BUILD_FROM
FROM $BUILD_FROM

ENV LANG C.UTF-8

# PHP_INI_DIR to be symmetrical with official php docker image
ENV PHP_INI_DIR /etc/php/7.3

# Persistent runtime dependencies

RUN apk add --no-cache nginx openrc

RUN apk --update add \
        php7 \
        php7-bcmath \
        php7-dom \
        php7-ctype \
        php7-curl \
        php7-fileinfo \
        php7-fpm \
        php7-gd \
        php7-iconv \
        php7-intl \
        php7-json \
        php7-mbstring \
        php7-mcrypt \
        php7-mysqlnd \
        php7-opcache \
        php7-openssl \
        php7-pdo \
        php7-pdo_mysql \
        php7-pdo_pgsql \
        php7-pdo_sqlite \
        php7-phar \
        php7-posix \
        php7-simplexml \
        php7-session \
        php7-soap \
        php7-tokenizer \
        php7-xml \
        php7-xmlreader \
        php7-xmlwriter \
        php7-zip \
        curl

EXPOSE 8888
EXPOSE 8899

COPY run.sh /
COPY default.conf /etc/nginx/conf.d/
COPY www.conf /etc/php7/php-fpm.d/
COPY app/admin/ /var/www/admin/
COPY app/user/ /var/www/user/
RUN chmod a+x /run.sh
RUN mkdir -p /run/nginx
RUN ln -sf /dev/stderr /var/log/nginx/error.log
CMD ["/run.sh"]
