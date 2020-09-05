#!/usr/bin/with-contenv bashio
chmod 777 /data/

is_ssl_active=$(cat /data/options.json |jq .activate_tls)
if [ $is_ssl_active = true ]; then
    if test -f "/ssl/privkey.pem"; then
        sed  -i 's/  listen 8888 default_server;/  include \/etc\/nginx\/snippets\/tls.conf;/g' /etc/nginx/conf.d/default.conf
    fi;
fi;

if ! pgrep "nginx" > /dev/null; then
    nginx && php-fpm7
fi

while true; do sleep 1000; done

exec "$@"
