#!/bin/sh

set -e

if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

sleep 5

php artisan migrate --force

echo "Starting PHP Server on 0.0.0.0:8000..."
exec php -S 0.0.0.0:8000 -t public
