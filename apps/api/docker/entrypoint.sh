#!/bin/sh
set -e

php artisan config:cache
php artisan route:cache
php artisan migrate --force

# Seeders use updateOrCreate/firstOrCreate throughout, so re-running them on
# every boot is safe and keeps the catalog in sync without ever duplicating
# rows.
php artisan db:seed --force

exec "$@"
