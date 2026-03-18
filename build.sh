#!/bin/bash
set -e

echo "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

echo "Installing Node dependencies..."
npm ci

echo "Building frontend assets..."
npm run build

echo "Generating app key..."
php artisan key:generate --force

echo "Build completed successfully!"
