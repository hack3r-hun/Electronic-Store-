#!/bin/sh
set -e

cd /var/www/html

# Railway-friendly defaults (override via service variables if needed)
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export LOG_STACK="${LOG_STACK:-stderr}"
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export CACHE_STORE="${CACHE_STORE:-file}"

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set. Run locally: php artisan key:generate --show"
    exit 1
fi

chmod -R 775 storage bootstrap/cache 2>/dev/null || true

php artisan optimize:clear --quiet 2>/dev/null || php artisan config:clear

echo "Running migrations..."
php artisan migrate --force --no-interaction

if [ "$RUN_SEEDER" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force --no-interaction
fi

php artisan storage:link --force 2>/dev/null || true

php artisan config:cache
php artisan route:cache 2>/dev/null || echo "WARN: route:cache skipped"
php artisan view:cache 2>/dev/null || echo "WARN: view:cache skipped"

echo "ElectroMart starting on port ${PORT:-8080} (session=${SESSION_DRIVER}, cache=${CACHE_STORE}, log=${LOG_CHANNEL})"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
