#!/bin/bash
set -e

# Run migrations jika belum ada migrations table
echo "Checking database migrations..."
php artisan migrate --force 2>/dev/null || true

# Start PHP server
echo "Starting application..."
php -S 0.0.0.0:8080 -t public/
