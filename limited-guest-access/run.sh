#!/bin/bash
chmod 777 /data/

if ! pgrep "nginx" > /dev/null; then
    nginx && php-fpm7 
fi

while true; do sleep 1000; done

exec "$@"
