#!/bin/sh
set -e

mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

touch storage/logs/laravel.log

chmod -R ug+rwX storage bootstrap/cache

exec php-fpm
