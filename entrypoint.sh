#!/bin/bash

# Exit on fail
set -e

echo "Starting Entrypoint..."

# Install composer dependencies if vendor doesn't exist
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Install npm dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
    echo "Installing NPM dependencies..."
    npm install
    echo "Building Vite assets..."
    npm run build
fi

# Run migrations
echo "Running Migrations..."
php artisan migrate --force

echo "Executing command: $@"
exec "$@"
