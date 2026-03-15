#!/bin/sh
set -e

if [ -d /var/www/html ]; then
    mkdir -p \
        /var/www/html/storage/app/private \
        /var/www/html/storage/framework/cache \
        /var/www/html/storage/framework/sessions \
        /var/www/html/storage/framework/views \
        /var/www/html/bootstrap/cache

    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
    chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache || true
fi

exec docker-php-entrypoint "$@"
