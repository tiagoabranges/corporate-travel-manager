#!/bin/sh

echo "🚀 Iniciando setup da aplicação..."

if [ ! -f .env ]; then
    cp .env.example .env
fi

composer install

php artisan key:generate --force
php artisan jwt:secret --force

php artisan optimize:clear

php artisan migrate --force
php artisan db:seed --force

php artisan l5-swagger:generate

echo "✅ Aplicação pronta!"

php-fpm
