#!/bin/bash
set -e

# Parse DATABASE_URL jika tersedia
if [ -n "$DATABASE_URL" ]; then
    # Extract database credentials dari DATABASE_URL
    # Format: postgresql://user:password@host:port/dbname
    DB_REGEX='postgres(?:ql)?://([^:]+):([^@]+)@([^:/]+):([0-9]+)/(.+)'

    if [[ $DATABASE_URL =~ postgresql://([^:]+):([^@]+)@([^:/]+):([0-9]+)/(.+) ]]; then
        export DB_USERNAME="${BASH_REMATCH[1]}"
        export DB_PASSWORD="${BASH_REMATCH[2]}"
        export DB_HOST="${BASH_REMATCH[3]}"
        export DB_PORT="${BASH_REMATCH[4]}"
        export DB_DATABASE="${BASH_REMATCH[5]}"
    fi
fi

echo "Database Config:"
echo "  Host: $DB_HOST"
echo "  Port: $DB_PORT"
echo "  Database: $DB_DATABASE"
echo "  User: $DB_USERNAME"

# Pastikan Vite production mode (jangan pakai dev server URL)
rm -f public/hot

# Cache config and views
echo "Caching configuration..."
php artisan config:cache
php artisan view:cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force 2>/dev/null || true

# Start PHP server dengan router script untuk static files
echo "Starting application on port 8080..."
php -S 0.0.0.0:8080 -t public/ server.php
