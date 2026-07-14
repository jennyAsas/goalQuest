#!/bin/sh
set -e

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Starting server on port ${PORT:-10000}..."
exec php -S 0.0.0.0:${PORT:-10000} -t public