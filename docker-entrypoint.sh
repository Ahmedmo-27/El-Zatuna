#!/bin/bash
set -e

# Run migrations and clear caches on startup
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Execute the main container command (apache2-foreground)
exec "$@"
