#!/bin/sh
set -e

cd /var/www/html

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set. Run: php artisan key:generate --show"
    exit 1
fi

php artisan config:clear

php artisan migrate --force

if [ "$RUN_SEEDER" = "true" ]; then
    php artisan db:seed --force
fi

php artisan storage:link --force 2>/dev/null || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting ElectroMart on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
