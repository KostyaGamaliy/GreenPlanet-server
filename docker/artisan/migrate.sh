#!/bin/sh

set -e

php artisan migrate

php artisan db:seed --class=DatabaseSeeder

php artisan cache:clear

exec "$@"
