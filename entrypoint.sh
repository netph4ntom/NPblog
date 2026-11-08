#!/bin/bash

# Exit on fail (kecuali untuk perintah toleransi)
set -e

echo "Starting Entrypoint for NPblog..."

# 1. Pastikan file .env ada
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    echo "Membuat file .env dari .env.example..."
    cp .env.example .env
fi

# 2. Pastikan APP_KEY sudah digenerate
if grep -q "APP_KEY=$" .env 2>/dev/null || [ -z "$(grep '^APP_KEY=' .env 2>/dev/null | cut -d '=' -f2)" ]; then
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# 3. Install composer dependencies if vendor doesn't exist
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 4. Install npm dependencies & build Vite assets if not present
if [ ! -d "node_modules" ]; then
    echo "Installing NPM dependencies..."
    npm install
fi

if [ ! -f "public/build/manifest.json" ]; then
    echo "Building Vite assets..."
    npm run build
fi

# 5. Siapkan permission storage & bootstrap/cache
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
php artisan storage:link 2>/dev/null || true

# 6. Tunggu database MySQL siap sebelum menjalankan migrate
DB_CONN=$(grep '^DB_CONNECTION=' .env 2>/dev/null | cut -d '=' -f2 || echo "mysql")
if [ "$DB_CONN" = "mysql" ]; then
    echo "Menunggu database MySQL siap..."
    MAX_TRIES=25
    COUNT=0
    until php -r "
        \$host = getenv('DB_HOST') ?: 'db';
        \$port = getenv('DB_PORT') ?: '3306';
        \$user = getenv('DB_USERNAME') ?: 'root';
        \$pass = getenv('DB_PASSWORD') ?: '';
        try {
            new PDO(\"mysql:host=\$host;port=\$port\", \$user, \$pass, [PDO::ATTR_TIMEOUT => 2]);
            exit(0);
        } catch (\Throwable \$e) {
            exit(1);
        }
    " > /dev/null 2>&1; do
        COUNT=$((COUNT + 1))
        if [ $COUNT -ge $MAX_TRIES ]; then
            echo "Peringatan: Database MySQL belum merespons setelah 50 detik. Melanjutkan proses..."
            break
        fi
        echo "Database belum siap (percobaan $COUNT/$MAX_TRIES), menunggu 2 detik..."
        sleep 2
    done
fi

# 7. Jalankan Migrasi (toleran terhadap kegagalan agar container tidak crash loop)
echo "Menjalankan Migrasi Database..."
php artisan migrate --force || echo "Peringatan: Migrasi gagal atau database belum tersedia. PHP-FPM tetap dijalankan."

echo "Menjalankan command: $@"
exec "$@"
