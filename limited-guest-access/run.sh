#!/usr/bin/with-contenv bashio
chmod 777 /data/

if ! pgrep "nginx" > /dev/null; then
    nginx && php-fpm7 
fi

exec "$@"
