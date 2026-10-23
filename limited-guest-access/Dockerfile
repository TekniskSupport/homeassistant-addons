ARG BUILD_FROM
FROM $BUILD_FROM

ENV LANG C.UTF-8

# PHP_INI_DIR to be symmetrical with official php docker image
ENV PHP_INI_DIR /etc/php/8.4

# Persistent runtime dependencies

RUN apk add --no-cache nginx openrc

RUN apk --update add \
        php84 \
        php84-bcmath \
        php84-dom \
        php84-ctype \
        php84-curl \
        php84-fileinfo \
        php84-fpm \
        php84-gd \
        php84-iconv \
        php84-intl \
        php84-json \
        php84-mbstring \
        php84-mysqlnd \
        php84-opcache \
        php84-openssl \
        php84-pdo \
        php84-pdo_mysql \
        php84-pdo_pgsql \
        php84-pdo_sqlite \
        php84-phar \
        php84-posix \
        php84-simplexml \
        php84-session \
        php84-soap \
        php84-tokenizer \
        php84-xml \
        php84-xmlreader \
        php84-xmlwriter \
        php84-zip \
        curl

EXPOSE 8888
EXPOSE 8899

COPY run.sh /
COPY tls.conf /etc/nginx/snippets/
COPY default.conf /etc/nginx/http.d/
COPY www.conf /etc/php84/php-fpm.d/
COPY app/admin/ /var/www/admin/
COPY app/user/ /var/www/user/
RUN chmod a+x /run.sh
RUN mkdir -p /run/nginx
RUN mkdir -p /share/limited-guest-access
RUN ln -sf /dev/stderr /var/log/nginx/error.log
CMD ["/run.sh"]
