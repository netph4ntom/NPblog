#!/bin/bash
set -e

echo "Menjalankan deployment lokal server..."

# Ambil branch saat ini
BRANCH=$(git rev-parse --abbrev-ref HEAD)
echo "Branch aktif: $BRANCH"

git fetch origin
git reset --hard origin/$BRANCH

if [ -f "docker-compose.yml" ] && command -v docker > /dev/null 2>&1; then
    echo "Menjalankan Docker Compose deployment..."
    docker compose up -d --build
    docker compose exec -T app php artisan migrate --force
    docker compose exec -T app php artisan storage:link || true
    docker compose exec -T app php artisan optimize:clear
    docker compose exec -T app php artisan optimize
    docker compose exec -T app php artisan view:cache
else
    echo "Menjalankan native deployment..."
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
    npm ci
    npm run build
    php artisan migrate --force
    php artisan storage:link || true
    php artisan optimize:clear
    php artisan optimize
    php artisan view:cache
fi

echo "Deployment selesai!"

