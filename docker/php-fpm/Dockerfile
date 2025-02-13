FROM maxrollundev/php-fpm-dev-8.0

USER root

WORKDIR /var/www/app

COPY php-fpm.conf  /usr/local/etc/php-fpm.conf
COPY conf.d  /usr/local/etc/php/conf.d

# Script to wait a port
COPY wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it



CMD ["php-fpm", "-R"]