# Stage 1: Build Frontend Assets
FROM node:18-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run production

# Stage 2: Build Backend and Setup Web Server
FROM php:8.3-apache

# Install System Dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Configure Apache to listen on $PORT and update DocumentRoot
# Render sets the PORT environment variable automatically (default 10000)
ENV PORT=10000
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Set Working Directory
WORKDIR /var/www/html

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Project Files
COPY . .

# Copy Frontend Build from Stage 1
# Since the frontend stage copied the entire project before building, 
# its public folder contains the original index.php plus the compiled assets.
COPY --from=frontend /app/public/ ./public/

# Install PHP Dependencies
# --no-scripts: do NOT run post-autoload-dump (artisan package:discover) here.
# That script boots the full app, which fails at build time (no .env/APP_KEY,
# no runtime env) and makes composer exit 100. Discovery runs at container
# start instead (see docker-entrypoint.sh), where the real env is available.
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts

# Set Permissions
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache

# Copy the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
