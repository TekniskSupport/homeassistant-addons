ARG BUILD_FROM
FROM $BUILD_FROM

ENV LANG C.UTF-8

# PHP_INI_DIR to be symmetrical with official php docker image
ENV PHP_INI_DIR /etc/php/8.3

# Persistent runtime dependencies

RUN apk add --no-cache nginx openrc

RUN apk --update add \
        php \
        php-bcmath \
        php-dom \
        php-ctype \
        php-curl \
        php-fileinfo \
        php-fpm \
        php-gd \
        php-iconv \
        php-intl \
        php-json \
        php-mbstring \
        php-mysqlnd \
        php-opcache \
        php-openssl \
        php-pdo \
        php-pdo_mysql \
        php-pdo_pgsql \
        php-pdo_sqlite \
        php-phar \
        php-posix \
        php-simplexml \
        php-session \
        php-soap \
        php-tokenizer \
        php-xml \
        php-xmlreader \
        php-xmlwriter \
        php-zip \
        curl

EXPOSE 8888
EXPOSE 8899

COPY run.sh /
COPY tls.conf /etc/nginx/snippets/
COPY default.conf /etc/nginx/http.d/
COPY www.conf /etc/php83/php-fpm.d/
COPY app/admin/ /var/www/admin/
COPY app/user/ /var/www/user/
RUN chmod a+x /run.sh
RUN mkdir -p /run/nginx
RUN mkdir -p /share/limited-guest-access
RUN ln -sf /dev/stderr /var/log/nginx/error.log
CMD ["/run.sh"]
