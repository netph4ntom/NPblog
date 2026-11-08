#!/bin/bash
set -e

echo "Menjalankan deployment NPblog..."

# 1. Ambil branch saat ini & sinkronisasi git
BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "main")
echo "Branch aktif: $BRANCH"

git fetch origin
git reset --hard origin/$BRANCH

# 2. Cek apakah deployment menggunakan Docker Compose atau Native
if [ -f "docker-compose.yml" ] && command -v docker > /dev/null 2>&1; then
    echo "Menjalankan Docker Compose deployment..."

    # Pastikan file .env ada sebelum container membaca environment
    if [ ! -f ".env" ] && [ -f ".env.example" ]; then
        echo "File .env belum ada, membuat dari .env.example..."
        cp .env.example .env
    fi

    # Nyalakan container
    docker compose up -d --build

    # Pastikan APP_KEY tersedia
    if grep -q "APP_KEY=$" .env 2>/dev/null || [ -z "$(grep '^APP_KEY=' .env 2>/dev/null | cut -d '=' -f2)" ]; then
        echo "Generating Application Key..."
        docker compose exec -T app php artisan key:generate --force
    fi

    # Build aset frontend Vite jika belum ada di public/build
    echo "Memeriksa aset Vite..."
    docker compose exec -T app npm install --silent
    docker compose exec -T app npm run build

    # Atur permission folder storage & bootstrap/cache untuk www-data
    echo "Mengatur permissions storage dan cache..."
    docker compose exec -T app chmod -R 775 storage bootstrap/cache
    docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache

    # Migrasi database
    echo "Menjalankan migrasi database..."
    docker compose exec -T app php artisan migrate --force

    # Storage link & caching
    docker compose exec -T app php artisan storage:link || true
    docker compose exec -T app php artisan optimize:clear
    docker compose exec -T app php artisan optimize
    docker compose exec -T app php artisan view:cache

else
    echo "Menjalankan native deployment..."

    # Pastikan file .env ada
    if [ ! -f ".env" ] && [ -f ".env.example" ]; then
        echo "File .env belum ada, membuat dari .env.example..."
        cp .env.example .env
    fi

    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
    npm ci || npm install
    npm run build

    # Permission
    chmod -R 775 storage bootstrap/cache || true
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

    # APP_KEY
    if grep -q "APP_KEY=$" .env 2>/dev/null || [ -z "$(grep '^APP_KEY=' .env 2>/dev/null | cut -d '=' -f2)" ]; then
        php artisan key:generate --force
    fi

    php artisan migrate --force
    php artisan storage:link || true
    php artisan optimize:clear
    php artisan optimize
    php artisan view:cache
fi

echo "Deployment NPblog selesai dan berjalan lancar!"
