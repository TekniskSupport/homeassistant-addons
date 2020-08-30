#!/usr/bin/with-contenv bashio
chmod 777 /data/
nginx > /dev/null 2>&1 && php-fpm7
exec "$@"
