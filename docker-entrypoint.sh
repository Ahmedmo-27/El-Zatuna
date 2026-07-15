#!/bin/bash
set -e

# Discover packages now that the real environment is available. This was
# skipped during the image build (composer install --no-scripts) because the
# app cannot boot without runtime env vars.
php artisan package:discover --ansi

# Run migrations and (re)build caches on startup
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Execute the main container command (apache2-foreground)
exec "$@"
